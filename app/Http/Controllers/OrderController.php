<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Tampilkan halaman POS (Operasional Kasir).
     */
    public function index(): View
    {
        // Ambil produk yang aktif beserta resepnya
        $products = Product::where('is_active', true)
            ->with('ingredients')
            ->orderBy('name')
            ->get();

        // Ambil daftar kategori produk yang aktif
        $categories = Product::where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->toArray();

        return view('pos.index', compact('products', 'categories'));
    }

    /**
     * Proses transaksi penjualan & potong stok otomatis.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            // 1. Generate Nomor Invoice Otomatis (Format: INV-YYYYMMDD-0001)
            $today = now()->format('Ymd');
            $lastOrder = Order::whereDate('created_at', now()->toDateString())
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            $number = 1;
            if ($lastOrder && preg_match('/INV-\d{8}-(\d{4})/', $lastOrder->invoice_number, $matches)) {
                $number = (int)$matches[1] + 1;
            }
            $invoiceNumber = 'INV-' . $today . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

            // 2. Hitung subtotal dan detail produk secara aman dari database (bukan dari client)
            $subtotal = 0;
            $itemsToProcess = [];

            foreach ($request->input('items') as $item) {
                $product = Product::with('ingredients')->find($item['id']);

                if (!$product) {
                    throw new \Exception("Produk dengan ID {$item['id']} tidak ditemukan.");
                }

                if (!$product->is_active) {
                    throw new \Exception("Produk [{$product->name}] sedang tidak aktif.");
                }

                $qty = (int)$item['qty'];
                $price = $product->price;
                $lineTotal = $price * $qty;
                $subtotal += $lineTotal;

                $itemsToProcess[] = [
                    'product'  => $product,
                    'quantity' => $qty,
                    'price'    => $price,
                ];
            }

            $discount = (float)$request->input('discount', 0);
            $taxableAmount = max(0, $subtotal - $discount);
            
            // Pajak PB1 10%
            $tax = $taxableAmount * 0.10;
            
            // Total bersih yang dibayar pelanggan
            $totalPay = $taxableAmount + $tax;

            // 3. Simpan data Order utama
            $order = Order::create([
                'invoice_number' => $invoiceNumber,
                'order_type'     => $request->input('order_type'),
                'table_number'   => $request->input('order_type') === 'dine_in' ? $request->input('table_number') : null,
                'payment_method' => $request->input('payment_method'),
                'subtotal'       => $subtotal,
                'tax'            => $tax,
                'discount'       => $discount,
                'total_pay'      => $totalPay,
                'cashier_id'     => Auth::id() ?? 1,
            ]);

            // 4. Proses Pivot Detail Order dan Pemotongan Stok
            foreach ($itemsToProcess as $item) {
                $product = $item['product'];
                $quantityPurchased = $item['quantity'];
                $priceLocked = $item['price'];

                // Simpan ke tabel pivot order_product
                $order->products()->attach($product->id, [
                    'quantity' => $quantityPurchased,
                    'price'    => $priceLocked,
                ]);

                // Looping bahan baku resep untuk menu produk ini
                foreach ($product->ingredients as $ingredient) {
                    $recipeQty = (float)$ingredient->pivot->quantity;
                    $requiredQty = $recipeQty * $quantityPurchased;

                    // Lock data bahan baku dari database
                    $dbIngredient = Ingredient::lockForUpdate()->find($ingredient->id);

                    if (!$dbIngredient) {
                        throw new \Exception("Bahan baku [{$ingredient->name}] tidak ditemukan di sistem.");
                    }

                    // Hitung sisa stok
                    $newStock = $dbIngredient->stock - $requiredQty;

                    // Failsafe: Jika stok tidak cukup, batalkan transaksi
                    if ($newStock < 0) {
                        throw new \Exception("Stok bahan baku [{$dbIngredient->name}] tidak mencukupi.");
                    }

                    // Update stok langsung ke database
                    $dbIngredient->stock = $newStock;
                    $dbIngredient->save();
                }
            }

            DB::commit();

            // Load data relasi untuk struk belanja
            $order->load(['cashier', 'products']);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi penjualan berhasil disimpan.',
                'data'    => $order,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
