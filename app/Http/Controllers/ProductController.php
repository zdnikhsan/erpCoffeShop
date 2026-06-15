<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $products = Product::query()
            ->with('ingredients')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $ingredients = Ingredient::orderBy('name')->get();
        return view('products.create', compact('ingredients'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $productData = $request->safe()->except('ingredients');
            $productData['is_active'] = $request->boolean('is_active', true);

            $product = Product::create($productData);

            $syncData = [];
            foreach ($request->input('ingredients', []) as $item) {
                if (!empty($item['ingredient_id'])) {
                    $syncData[$item['ingredient_id']] = [
                        'quantity' => $item['quantity']
                    ];
                }
            }
            $product->ingredients()->sync($syncData);
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk dan resep berhasil ditambahkan.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): View
    {
        $product->load('ingredients');
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $product->load('ingredients');
        $ingredients = Ingredient::orderBy('name')->get();
        
        return view('products.edit', compact('product', 'ingredients'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product) {
            $productData = $request->safe()->except('ingredients');
            $productData['is_active'] = $request->boolean('is_active', false);

            $product->update($productData);

            $syncData = [];
            foreach ($request->input('ingredients', []) as $item) {
                if (!empty($item['ingredient_id'])) {
                    $syncData[$item['ingredient_id']] = [
                        'quantity' => $item['quantity']
                    ];
                }
            }
            $product->ingredients()->sync($syncData);
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk dan resep berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Fail-safe: produk tidak boleh dihapus jika sudah pernah ada di dalam transaksi penjualan (tabel orders/order_items yang akan dibuat nanti)
        $isLinkedToOrders = false;

        if (Schema::hasTable('order_items')) {
            $isLinkedToOrders = DB::table('order_items')->where('product_id', $product->id)->exists();
        } elseif (Schema::hasTable('order_details')) {
            $isLinkedToOrders = DB::table('order_details')->where('product_id', $product->id)->exists();
        } elseif (Schema::hasTable('orders')) {
            // Check if orders table exists and has direct reference (just in case)
            $isLinkedToOrders = DB::table('orders')->where('product_id', $product->id)->exists();
        }

        if ($isLinkedToOrders) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Produk tidak dapat dihapus karena sudah ada di dalam transaksi penjualan.');
        }

        DB::transaction(function () use ($product) {
            $product->ingredients()->detach();
            $product->delete();
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
