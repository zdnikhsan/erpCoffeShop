<?php

use App\Models\Ingredient;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

beforeEach(function () {
    // Seed Spatie roles
    Role::firstOrCreate(['name' => 'owner']);
    Role::firstOrCreate(['name' => 'manager']);
    Role::firstOrCreate(['name' => 'cashier']);
});

test('unauthenticated users cannot access purchase orders routes', function () {
    $this->get(route('purchase-orders.index'))->assertRedirect('/login');
    $this->get(route('purchase-orders.create'))->assertRedirect('/login');
    $this->post(route('purchase-orders.store'), [])->assertRedirect('/login');
});

test('cashier role cannot access purchase orders routes', function () {
    $cashier = User::factory()->create();
    $cashier->assignRole('cashier');

    $this->actingAs($cashier)->get(route('purchase-orders.index'))->assertStatus(403);
    $this->actingAs($cashier)->get(route('purchase-orders.create'))->assertStatus(403);
});

test('owner and manager roles can access purchase orders routes', function () {
    $owner = User::factory()->create();
    $owner->assignRole('owner');

    $manager = User::factory()->create();
    $manager->assignRole('manager');

    $this->actingAs($owner)->get(route('purchase-orders.index'))->assertOk();
    $this->actingAs($manager)->get(route('purchase-orders.index'))->assertOk();
});

test('owner can store a new purchase order and total_amount and po_number are computed automatically', function () {
    $owner = User::factory()->create();
    $owner->assignRole('owner');

    $supplier = Supplier::create([
        'name' => 'Supplier A',
        'phone' => '08123456789',
        'address' => 'Alamat A',
        'payment_terms' => 7,
    ]);

    $ingredient1 = Ingredient::create([
        'name' => 'Biji Kopi Gayo',
        'sku' => 'BB-GAYO-01',
        'stock' => 500,
        'unit' => 'gram',
        'safety_stock' => 50,
    ]);

    $ingredient2 = Ingredient::create([
        'name' => 'Susu Segar',
        'sku' => 'BB-MILK-01',
        'stock' => 1000,
        'unit' => 'ml',
        'safety_stock' => 100,
    ]);

    $poData = [
        'supplier_id' => $supplier->id,
        'order_date' => '2026-06-11',
        'ingredients' => [
            [
                'ingredient_id' => $ingredient1->id,
                'quantity' => '10.00',
                'unit_price' => '2000.00', // Subtotal = 20000.00
            ],
            [
                'ingredient_id' => $ingredient2->id,
                'quantity' => '5.00',
                'unit_price' => '1500.00', // Subtotal = 7500.00
            ],
        ],
    ];

    $response = $this->actingAs($owner)->post(route('purchase-orders.store'), $poData);

    $response->assertRedirect(route('purchase-orders.index'));
    $response->assertSessionHas('success', 'Purchase Order berhasil dibuat.');

    $po = PurchaseOrder::latest()->first();
    // Validate auto-generated PO number format: PO-YYYYMM-0001
    expect($po->po_number)->toBe('PO-202606-0001');
    expect($po->supplier_id)->toBe($supplier->id);
    expect($po->status)->toBe('draft');
    expect((float) $po->total_amount)->toBe(27500.00);
    expect($po->order_date->format('Y-m-d'))->toBe('2026-06-11');
    expect($po->ingredients)->toHaveCount(2);

    $pivot1 = $po->ingredients->firstWhere('id', $ingredient1->id)->pivot;
    expect((float) $pivot1->quantity)->toBe(10.0);
    expect((float) $pivot1->quantity_received)->toBe(0.0);
    expect((float) $pivot1->unit_price)->toBe(2000.0);
});

test('owner can update draft purchase order details', function () {
    $owner = User::factory()->create();
    $owner->assignRole('owner');

    $supplier = Supplier::create([
        'name' => 'Supplier B',
        'phone' => '08123456789',
        'address' => 'Alamat B',
        'payment_terms' => 0,
    ]);

    $ingredient = Ingredient::create([
        'name' => 'Gula Pasir',
        'sku' => 'BB-SUGAR-01',
        'stock' => 300,
        'unit' => 'pcs',
        'safety_stock' => 10,
    ]);

    $po = PurchaseOrder::create([
        'supplier_id' => $supplier->id,
        'order_date' => '2026-06-11',
        'status' => 'draft',
        'total_amount' => 1000.00,
    ]);
    $po->ingredients()->sync([
        $ingredient->id => ['quantity' => 2, 'unit_price' => 500.00]
    ]);

    // Update PO details
    $updateData = [
        'supplier_id' => $supplier->id,
        'order_date' => '2026-06-12',
        'ingredients' => [
            [
                'ingredient_id' => $ingredient->id,
                'quantity' => '5.00',
                'unit_price' => '400.00', // Total = 2000.00
            ]
        ]
    ];

    $response = $this->actingAs($owner)->put(route('purchase-orders.update', $po), $updateData);

    $response->assertRedirect(route('purchase-orders.index'));
    $response->assertSessionHas('success', 'Purchase Order berhasil diperbarui.');

    $po->refresh();
    expect((float) $po->total_amount)->toBe(2000.00);
    expect($po->order_date->format('Y-m-d'))->toBe('2026-06-12');
    expect((float) $po->ingredients->first()->pivot->quantity)->toBe(5.0);
});

test('owner cannot update purchase order if status is not draft', function () {
    $owner = User::factory()->create();
    $owner->assignRole('owner');

    $supplier = Supplier::create([
        'name' => 'Supplier C',
        'phone' => '08123456789',
        'address' => 'Alamat C',
        'payment_terms' => 0,
    ]);

    $po = PurchaseOrder::create([
        'supplier_id' => $supplier->id,
        'order_date' => '2026-06-11',
        'status' => 'sent',
        'total_amount' => 1000.00,
    ]);

    $ingredient = Ingredient::create([
        'name' => 'Gula Pasir',
        'sku' => 'BB-SUGAR-02',
        'stock' => 300,
        'unit' => 'pcs',
        'safety_stock' => 10,
    ]);

    $updateData = [
        'supplier_id' => $supplier->id,
        'order_date' => '2026-06-12',
        'ingredients' => [
            [
                'ingredient_id' => $ingredient->id,
                'quantity' => '5.00',
                'unit_price' => '400.00',
            ]
        ]
    ];

    $response = $this->actingAs($owner)->put(route('purchase-orders.update', $po), $updateData);
    $response->assertRedirect(route('purchase-orders.index'));
    $response->assertSessionHas('error', 'Hanya Purchase Order berstatus Draft yang dapat diperbarui.');
});

test('transitioning PO to completed updates received quantities and increments ingredient stocks within a transaction', function () {
    $owner = User::factory()->create();
    $owner->assignRole('owner');

    $supplier = Supplier::create([
        'name' => 'Supplier D',
        'phone' => '08123456789',
        'address' => 'Alamat D',
        'payment_terms' => 0,
    ]);

    $ingredient1 = Ingredient::create([
        'name' => 'Cokelat Bubuk',
        'sku' => 'BB-CHOCO-01',
        'stock' => 10.00,
        'unit' => 'pack',
        'safety_stock' => 2,
    ]);

    $ingredient2 = Ingredient::create([
        'name' => 'Sirup Caramel',
        'sku' => 'BB-SYRUP-01',
        'stock' => 5.00,
        'unit' => 'botol',
        'safety_stock' => 1,
    ]);

    $po = PurchaseOrder::create([
        'supplier_id' => $supplier->id,
        'order_date' => '2026-06-11',
        'status' => 'on_delivery',
        'total_amount' => 5000.00,
    ]);
    $po->ingredients()->sync([
        $ingredient1->id => ['quantity' => 10.00, 'unit_price' => 300.00],
        $ingredient2->id => ['quantity' => 5.00, 'unit_price' => 400.00],
    ]);

    // Transition to completed
    $statusData = [
        'status' => 'completed',
        'received_date' => '2026-06-12',
        'ingredients' => [
            [
                'ingredient_id' => $ingredient1->id,
                'quantity_received' => '9.50', // Ordered 10, received 9.5
            ],
            [
                'ingredient_id' => $ingredient2->id,
                'quantity_received' => '5.00', // Ordered 5, received 5
            ]
        ]
    ];

    $response = $this->actingAs($owner)->patch(route('purchase-orders.update-status', $po), $statusData);

    $response->assertRedirect(route('purchase-orders.show', $po));
    $response->assertSessionHas('success', 'Status Purchase Order berhasil diperbarui.');

    $po->refresh();
    expect($po->status)->toBe('completed');
    expect($po->received_date->format('Y-m-d'))->toBe('2026-06-12');

    // Verify stock is incremented correctly
    $ingredient1->refresh();
    $ingredient2->refresh();

    // 10.00 + 9.50 = 19.50
    expect((float) $ingredient1->stock)->toBe(19.50);
    // 5.00 + 5.00 = 10.00
    expect((float) $ingredient2->stock)->toBe(10.00);

    // Verify quantity_received in pivot table
    $po->refresh();
    $pivotChoco = $po->ingredients->firstWhere('id', $ingredient1->id)->pivot;
    $pivotSyrup = $po->ingredients->firstWhere('id', $ingredient2->id)->pivot;
    expect((float) $pivotChoco->quantity_received)->toBe(9.50);
    expect((float) $pivotSyrup->quantity_received)->toBe(5.00);
});

test('transitioning PO blocks further status updates once completed', function () {
    $owner = User::factory()->create();
    $owner->assignRole('owner');

    $supplier = Supplier::create(['name' => 'Supplier E', 'phone' => '01', 'address' => 'Addr', 'payment_terms' => 0]);
    $po = PurchaseOrder::create(['supplier_id' => $supplier->id, 'order_date' => '2026-06-11', 'status' => 'completed', 'total_amount' => 100]);

    $response = $this->actingAs($owner)->patch(route('purchase-orders.update-status', $po), ['status' => 'cancelled']);
    $response->assertRedirect(route('purchase-orders.show', $po));
    $response->assertSessionHas('error', 'Status Purchase Order yang sudah Selesai atau Dibatalkan tidak dapat diubah lagi.');
});
