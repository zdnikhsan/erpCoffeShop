<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderStatusRequest;
use App\Models\Ingredient;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of purchase orders.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $purchaseOrders = PurchaseOrder::query()
            ->with(['supplier', 'ingredients'])
            ->when($search, function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', fn ($s) => $s->where('name', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('purchase-orders.index', compact('purchaseOrders', 'search'));
    }

    /**
     * Show the form for creating a new purchase order.
     */
    public function create(): View
    {
        $suppliers = Supplier::orderBy('name')->get();
        $ingredients = Ingredient::orderBy('name')->get();

        return view('purchase-orders.create', compact('suppliers', 'ingredients'));
    }

    /**
     * Store a newly created purchase order in storage.
     */
    public function store(StorePurchaseOrderRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $ingredientsData = [];

            foreach ($request->input('ingredients', []) as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $subtotal;

                $ingredientsData[$item['ingredient_id']] = [
                    'quantity'          => $item['quantity'],
                    'unit_price'        => $item['unit_price'],
                    'quantity_received' => 0.00,
                ];
            }

            $purchaseOrder = PurchaseOrder::create([
                'supplier_id'  => $request->input('supplier_id'),
                'order_date'   => $request->input('order_date'),
                'status'       => 'draft',
                'total_amount' => $totalAmount,
            ]);

            $purchaseOrder->ingredients()->sync($ingredientsData);

            DB::commit();

            return redirect()
                ->route('purchase-orders.index')
                ->with('success', 'Purchase Order berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan Purchase Order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified purchase order.
     */
    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load(['supplier', 'ingredients']);

        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified purchase order.
     */
    public function edit(PurchaseOrder $purchaseOrder): View|RedirectResponse
    {
        if ($purchaseOrder->status !== 'draft') {
            return redirect()
                ->route('purchase-orders.index')
                ->with('error', 'Hanya Purchase Order berstatus Draft yang dapat diubah.');
        }

        $purchaseOrder->load('ingredients');
        $suppliers = Supplier::orderBy('name')->get();
        $ingredients = Ingredient::orderBy('name')->get();

        return view('purchase-orders.edit', compact('purchaseOrder', 'suppliers', 'ingredients'));
    }

    /**
     * Update the specified purchase order in storage.
     */
    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->status !== 'draft') {
            return redirect()
                ->route('purchase-orders.index')
                ->with('error', 'Hanya Purchase Order berstatus Draft yang dapat diperbarui.');
        }

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $ingredientsData = [];

            foreach ($request->input('ingredients', []) as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $subtotal;

                $ingredientsData[$item['ingredient_id']] = [
                    'quantity'          => $item['quantity'],
                    'unit_price'        => $item['unit_price'],
                    'quantity_received' => 0.00,
                ];
            }

            $purchaseOrder->update([
                'supplier_id'  => $request->input('supplier_id'),
                'order_date'   => $request->input('order_date'),
                'total_amount' => $totalAmount,
            ]);

            $purchaseOrder->ingredients()->sync($ingredientsData);

            DB::commit();

            return redirect()
                ->route('purchase-orders.index')
                ->with('success', 'Purchase Order berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui Purchase Order: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified purchase order from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->status !== 'draft' && $purchaseOrder->status !== 'cancelled') {
            return redirect()
                ->route('purchase-orders.index')
                ->with('error', 'Hanya Purchase Order berstatus Draft atau Cancelled yang dapat dihapus.');
        }

        try {
            DB::beginTransaction();

            $purchaseOrder->ingredients()->detach();
            $purchaseOrder->delete();

            DB::commit();

            return redirect()
                ->route('purchase-orders.index')
                ->with('success', 'Purchase Order berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('purchase-orders.index')
                ->with('error', 'Gagal menghapus Purchase Order: ' . $e->getMessage());
        }
    }

    /**
     * Update status of the Purchase Order.
     */
    public function updateStatus(UpdatePurchaseOrderStatusRequest $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $newStatus = $request->input('status');

        // Prevent changing status if PO is already in a terminal state (completed or cancelled)
        if ($purchaseOrder->status === 'completed' || $purchaseOrder->status === 'cancelled') {
            return redirect()
                ->route('purchase-orders.show', $purchaseOrder)
                ->with('error', 'Status Purchase Order yang sudah Selesai atau Dibatalkan tidak dapat diubah lagi.');
        }

        try {
            DB::beginTransaction();

            if ($newStatus === 'completed') {
                $receivedDate = $request->input('received_date', now()->format('Y-m-d'));
                
                // Get list of currently attached ingredients to ensure we only process valid ones
                $poIngredients = $purchaseOrder->ingredients->keyBy('id');

                foreach ($request->input('ingredients', []) as $item) {
                    $ingredientId = $item['ingredient_id'];
                    $qtyReceived = $item['quantity_received'];

                    if (!$poIngredients->has($ingredientId)) {
                        throw new \Exception("Bahan baku dengan ID {$ingredientId} tidak ditemukan dalam daftar pesanan PO ini.");
                    }

                    // Update quantity_received in pivot table
                    $purchaseOrder->ingredients()->updateExistingPivot($ingredientId, [
                        'quantity_received' => $qtyReceived
                    ]);

                    // Load ingredient with exclusive lock to safely increment stock
                    $ingredient = Ingredient::lockForUpdate()->find($ingredientId);
                    if ($ingredient) {
                        $ingredient->increment('stock', $qtyReceived);
                    }
                }

                // Update PO status and received_date
                $purchaseOrder->update([
                    'status'        => 'completed',
                    'received_date' => $receivedDate,
                ]);
            } else {
                // If transitioning to any other status (sent, on_delivery, cancelled)
                $purchaseOrder->update([
                    'status' => $newStatus,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('purchase-orders.show', $purchaseOrder)
                ->with('success', 'Status Purchase Order berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui status Purchase Order: ' . $e->getMessage());
        }
    }
}
