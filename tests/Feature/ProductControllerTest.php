<?php

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    // Seed Spatie roles
    Role::firstOrCreate(['name' => 'owner']);
    Role::firstOrCreate(['name' => 'manager']);
    Role::firstOrCreate(['name' => 'cashier']);
});

test('unauthenticated users cannot access products routes', function () {
    $this->get(route('products.index'))->assertRedirect('/login');
    $this->get(route('products.create'))->assertRedirect('/login');
    $this->post(route('products.store'), [])->assertRedirect('/login');
});

test('cashier role cannot access products routes', function () {
    $cashier = User::factory()->create();
    $cashier->assignRole('cashier');

    $this->actingAs($cashier)->get(route('products.index'))->assertStatus(403);
    $this->actingAs($cashier)->get(route('products.create'))->assertStatus(403);
});

test('owner role can access products index', function () {
    $owner = User::factory()->create();
    $owner->assignRole('owner');

    $response = $this->actingAs($owner)->get(route('products.index'));
    $response->assertOk();
    $response->assertViewIs('products.index');
});

test('manager role can access products index', function () {
    $manager = User::factory()->create();
    $manager->assignRole('manager');

    $response = $this->actingAs($manager)->get(route('products.index'));
    $response->assertOk();
    $response->assertViewIs('products.index');
});

test('owner can store a new product with recipe ingredients', function () {
    $owner = User::factory()->create();
    $owner->assignRole('owner');

    $ingredient1 = Ingredient::create([
        'name' => 'Biji Kopi Arabika',
        'sku' => 'BB-ARABIKA-01',
        'stock' => 1000,
        'unit' => 'gram',
        'safety_stock' => 100,
    ]);

    $ingredient2 = Ingredient::create([
        'name' => 'Susu Cair',
        'sku' => 'BB-SUSU-01',
        'stock' => 5000,
        'unit' => 'ml',
        'safety_stock' => 500,
    ]);

    $productData = [
        'name' => 'Kopi Latte Special',
        'sku' => 'PRD-LATTE-SPC',
        'price' => 22000.00,
        'category' => 'Kopi',
        'is_active' => '1',
        'ingredients' => [
            [
                'ingredient_id' => $ingredient1->id,
                'quantity' => '18.00',
            ],
            [
                'ingredient_id' => $ingredient2->id,
                'quantity' => '150.00',
            ],
        ],
    ];

    $response = $this->actingAs($owner)->post(route('products.store'), $productData);

    $response->assertRedirect(route('products.index'));
    $response->assertSessionHas('success', 'Produk dan resep berhasil ditambahkan.');

    $this->assertDatabaseHas('products', [
        'name' => 'Kopi Latte Special',
        'sku' => 'PRD-LATTE-SPC',
        'price' => '22000.00',
        'category' => 'Kopi',
        'is_active' => true,
    ]);

    $product = Product::where('sku', 'PRD-LATTE-SPC')->first();
    expect($product->ingredients)->toHaveCount(2);

    $this->assertDatabaseHas('ingredient_product', [
        'product_id' => $product->id,
        'ingredient_id' => $ingredient1->id,
        'quantity' => '18.00',
    ]);
});

test('store product validation requires name, sku, price, category, and ingredients recipe', function () {
    $owner = User::factory()->create();
    $owner->assignRole('owner');

    $response = $this->actingAs($owner)->post(route('products.store'), []);

    $response->assertSessionHasErrors([
        'name',
        'sku',
        'price',
        'category',
        'ingredients',
    ]);
});

test('store product validation fails if ingredient quantity is 0 or negative', function () {
    $owner = User::factory()->create();
    $owner->assignRole('owner');

    $ingredient = Ingredient::create([
        'name' => 'Biji Kopi Arabika',
        'sku' => 'BB-ARABIKA-01',
        'stock' => 1000,
        'unit' => 'gram',
        'safety_stock' => 100,
    ]);

    $productData = [
        'name' => 'Kopi Latte Special',
        'sku' => 'PRD-LATTE-SPC',
        'price' => 22000.00,
        'category' => 'Kopi',
        'ingredients' => [
            [
                'ingredient_id' => $ingredient->id,
                'quantity' => '0.00',
            ]
        ]
    ];

    $response = $this->actingAs($owner)->post(route('products.store'), $productData);
    $response->assertSessionHasErrors(['ingredients.0.quantity']);
});

test('owner can update product details and ingredients sync successfully', function () {
    $owner = User::factory()->create();
    $owner->assignRole('owner');

    $ingredient1 = Ingredient::create([
        'name' => 'Biji Kopi Arabika',
        'sku' => 'BB-AR-01',
        'stock' => 1000,
        'unit' => 'gram',
        'safety_stock' => 100,
    ]);

    $ingredient2 = Ingredient::create([
        'name' => 'Susu Cair UHT',
        'sku' => 'BB-UHT-01',
        'stock' => 5000,
        'unit' => 'ml',
        'safety_stock' => 500,
    ]);

    $product = Product::create([
        'name' => 'Kopi Latte Awal',
        'sku' => 'PRD-LATTE-OLD',
        'price' => 15000.00,
        'category' => 'Kopi Awal',
        'is_active' => true,
    ]);

    $product->ingredients()->sync([
        $ingredient1->id => ['quantity' => '15.00']
    ]);

    // Update product: change metadata, drop ingredient1, add ingredient2
    $updateData = [
        'name' => 'Kopi Latte Baru',
        'sku' => 'PRD-LATTE-NEW',
        'price' => 18000.00,
        'category' => 'Kopi Baru',
        'is_active' => '0', // deactivate
        'ingredients' => [
            [
                'ingredient_id' => $ingredient2->id,
                'quantity' => '120.00',
            ],
        ],
    ];

    $response = $this->actingAs($owner)->put(route('products.update', $product), $updateData);

    $response->assertRedirect(route('products.index'));
    $response->assertSessionHas('success', 'Produk dan resep berhasil diperbarui.');

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Kopi Latte Baru',
        'sku' => 'PRD-LATTE-NEW',
        'price' => '18000.00',
        'category' => 'Kopi Baru',
        'is_active' => false,
    ]);

    $product->refresh();
    expect($product->ingredients)->toHaveCount(1);
    expect($product->ingredients->first()->id)->toBe($ingredient2->id);
});

test('product deletion is blocked if product exists in order items', function () {
    $owner = User::factory()->create();
    $owner->assignRole('owner');

    $ingredient = Ingredient::create([
        'name' => 'Biji Kopi Arabika',
        'sku' => 'BB-AR-02',
        'stock' => 1000,
        'unit' => 'gram',
        'safety_stock' => 100,
    ]);

    $product = Product::create([
        'name' => 'Kopi Cappuccino',
        'sku' => 'PRD-CAPPUCCINO',
        'price' => 25000.00,
        'category' => 'Kopi',
        'is_active' => true,
    ]);

    $product->ingredients()->sync([
        $ingredient->id => ['quantity' => '18.00']
    ]);

    // Create a mock order_items table dynamically for this test
    Schema::create('order_items', function ($table) {
        $table->id();
        $table->unsignedBigInteger('product_id');
        $table->integer('quantity');
        $table->decimal('price', 10, 2);
    });

    // Insert a sale referencing the product
    DB::table('order_items')->insert([
        'product_id' => $product->id,
        'quantity' => 1,
        'price' => 25000.00,
    ]);

    // Try deleting
    $response = $this->actingAs($owner)->delete(route('products.destroy', $product));

    $response->assertRedirect(route('products.index'));
    $response->assertSessionHas('error', 'Produk tidak dapat dihapus karena sudah ada di dalam transaksi penjualan.');

    // Product should still exist in database
    $this->assertDatabaseHas('products', ['id' => $product->id]);

    // Clean up
    Schema::dropIfExists('order_items');
});

test('owner can delete a product successfully if not linked to any order', function () {
    $owner = User::factory()->create();
    $owner->assignRole('owner');

    $ingredient = Ingredient::create([
        'name' => 'Biji Kopi Arabika',
        'sku' => 'BB-AR-03',
        'stock' => 1000,
        'unit' => 'gram',
        'safety_stock' => 100,
    ]);

    $product = Product::create([
        'name' => 'Kopi Espresso Solo',
        'sku' => 'PRD-ESPRESSO',
        'price' => 12000.00,
        'category' => 'Kopi',
        'is_active' => true,
    ]);

    $product->ingredients()->sync([
        $ingredient->id => ['quantity' => '9.00']
    ]);

    // Verify relations exist
    $this->assertDatabaseHas('ingredient_product', [
        'product_id' => $product->id,
        'ingredient_id' => $ingredient->id,
    ]);

    // Delete
    $response = $this->actingAs($owner)->delete(route('products.destroy', $product));

    $response->assertRedirect(route('products.index'));
    $response->assertSessionHas('success', 'Produk berhasil dihapus.');

    // Verify product and pivot entries are gone
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
    $this->assertDatabaseMissing('ingredient_product', [
        'product_id' => $product->id,
    ]);
});
