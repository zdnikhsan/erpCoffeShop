<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Seed sample data for Suppliers, Ingredients, Products & Recipes, and Purchase Orders.
     */
    public function run(): void
    {
        // ──────────────────────────────────────────────
        // 1. SUPPLIERS
        // ──────────────────────────────────────────────
        $suppliers = [
            [
                'name'          => 'PT Kopi Nusantara Jaya',
                'phone'         => '021-55512345',
                'address'       => 'Jl. Raya Bogor Km. 25 No. 10, Jakarta Timur',
                'payment_terms' => 30,
            ],
            [
                'name'          => 'CV Susu Murni Sejahtera',
                'phone'         => '0812-3456-7890',
                'address'       => 'Jl. Industri No. 88, Bandung, Jawa Barat',
                'payment_terms' => 14,
            ],
            [
                'name'          => 'UD Gula Manis Sentosa',
                'phone'         => '0878-1234-5678',
                'address'       => 'Jl. Pahlawan No. 5, Surabaya, Jawa Timur',
                'payment_terms' => 7,
            ],
            [
                'name'          => 'PT Sumber Bahan Bakery',
                'phone'         => '021-77789012',
                'address'       => 'Kawasan Industri Pulogadung Blok C-12, Jakarta Timur',
                'payment_terms' => 21,
            ],
        ];

        $supplierModels = [];
        foreach ($suppliers as $supplier) {
            $supplierModels[] = Supplier::create($supplier);
        }

        $this->command->info('✅ 4 Suppliers berhasil di-seed.');

        // ──────────────────────────────────────────────
        // 2. INGREDIENTS (Bahan Baku)
        // ──────────────────────────────────────────────
        $ingredients = [
            ['name' => 'Biji Kopi Arabika',       'sku' => 'BB-KOPI-ARB',  'stock' => 5000.00,  'unit' => 'gram',  'safety_stock' => 500.00],
            ['name' => 'Biji Kopi Robusta',        'sku' => 'BB-KOPI-ROB',  'stock' => 3000.00,  'unit' => 'gram',  'safety_stock' => 400.00],
            ['name' => 'Susu Full Cream',          'sku' => 'BB-SUSU-FC',   'stock' => 10000.00, 'unit' => 'ml',    'safety_stock' => 2000.00],
            ['name' => 'Susu Oat',                 'sku' => 'BB-SUSU-OAT',  'stock' => 5000.00,  'unit' => 'ml',    'safety_stock' => 1000.00],
            ['name' => 'Gula Aren Cair',           'sku' => 'BB-GULA-ARN',  'stock' => 3000.00,  'unit' => 'ml',    'safety_stock' => 500.00],
            ['name' => 'Gula Putih',               'sku' => 'BB-GULA-PTH',  'stock' => 5000.00,  'unit' => 'gram',  'safety_stock' => 800.00],
            ['name' => 'Cokelat Bubuk Premium',    'sku' => 'BB-CKLAT-BK',  'stock' => 2000.00,  'unit' => 'gram',  'safety_stock' => 300.00],
            ['name' => 'Matcha Powder',            'sku' => 'BB-MATCHA',    'stock' => 1000.00,  'unit' => 'gram',  'safety_stock' => 200.00],
            ['name' => 'Es Batu',                  'sku' => 'BB-ES-BATU',   'stock' => 20000.00, 'unit' => 'gram',  'safety_stock' => 5000.00],
            ['name' => 'Tepung Terigu Protein Tinggi', 'sku' => 'BB-TEPUNG-PT', 'stock' => 8000.00, 'unit' => 'gram', 'safety_stock' => 1500.00],
        ];

        $ingredientModels = [];
        foreach ($ingredients as $ingredient) {
            $ingredientModels[$ingredient['sku']] = Ingredient::create($ingredient);
        }

        $this->command->info('✅ 10 Ingredients berhasil di-seed.');

        // ──────────────────────────────────────────────
        // 3. PRODUCTS & RECIPES (Bill of Materials)
        // ──────────────────────────────────────────────
        $products = [
            [
                'name'      => 'Iced Latte',
                'sku'       => 'PRD-ICE-LAT',
                'price'     => 25000.00,
                'category'  => 'Coffee',
                'is_active' => true,
                'recipe'    => [
                    'BB-KOPI-ARB' => 18.00,   // 18g biji kopi
                    'BB-SUSU-FC'  => 150.00,  // 150ml susu
                    'BB-ES-BATU'  => 100.00,  // 100g es batu
                ],
            ],
            [
                'name'      => 'Es Kopi Susu Gula Aren',
                'sku'       => 'PRD-KOPI-AREN',
                'price'     => 23000.00,
                'category'  => 'Coffee',
                'is_active' => true,
                'recipe'    => [
                    'BB-KOPI-ROB' => 20.00,
                    'BB-SUSU-FC'  => 120.00,
                    'BB-GULA-ARN' => 30.00,
                    'BB-ES-BATU'  => 100.00,
                ],
            ],
            [
                'name'      => 'Cappuccino',
                'sku'       => 'PRD-CAPUCCNO',
                'price'     => 28000.00,
                'category'  => 'Coffee',
                'is_active' => true,
                'recipe'    => [
                    'BB-KOPI-ARB' => 18.00,
                    'BB-SUSU-FC'  => 180.00,
                ],
            ],
            [
                'name'      => 'Hot Chocolate',
                'sku'       => 'PRD-HOT-CHOC',
                'price'     => 22000.00,
                'category'  => 'Non-Coffee',
                'is_active' => true,
                'recipe'    => [
                    'BB-CKLAT-BK' => 25.00,
                    'BB-SUSU-FC'  => 200.00,
                    'BB-GULA-PTH' => 15.00,
                ],
            ],
            [
                'name'      => 'Iced Matcha Latte',
                'sku'       => 'PRD-MATCHA-L',
                'price'     => 27000.00,
                'category'  => 'Non-Coffee',
                'is_active' => true,
                'recipe'    => [
                    'BB-MATCHA'  => 10.00,
                    'BB-SUSU-FC' => 180.00,
                    'BB-GULA-PTH'=> 10.00,
                    'BB-ES-BATU' => 100.00,
                ],
            ],
            [
                'name'      => 'Oat Milk Latte',
                'sku'       => 'PRD-OAT-LAT',
                'price'     => 30000.00,
                'category'  => 'Coffee',
                'is_active' => true,
                'recipe'    => [
                    'BB-KOPI-ARB' => 18.00,
                    'BB-SUSU-OAT' => 180.00,
                    'BB-ES-BATU'  => 100.00,
                ],
            ],
            [
                'name'      => 'Iced Chocolate',
                'sku'       => 'PRD-ICE-CHOC',
                'price'     => 24000.00,
                'category'  => 'Non-Coffee',
                'is_active' => true,
                'recipe'    => [
                    'BB-CKLAT-BK' => 25.00,
                    'BB-SUSU-FC'  => 170.00,
                    'BB-GULA-PTH' => 15.00,
                    'BB-ES-BATU'  => 100.00,
                ],
            ],
            [
                'name'      => 'Espresso Single Shot',
                'sku'       => 'PRD-ESPRESSO',
                'price'     => 15000.00,
                'category'  => 'Coffee',
                'is_active' => false,
                'recipe'    => [
                    'BB-KOPI-ARB' => 14.00,
                ],
            ],
        ];

        foreach ($products as $productData) {
            $recipe = $productData['recipe'];
            unset($productData['recipe']);

            $product = Product::create($productData);

            $syncData = [];
            foreach ($recipe as $ingredientSku => $qty) {
                $ingredient = $ingredientModels[$ingredientSku];
                $syncData[$ingredient->id] = ['quantity' => $qty];
            }
            $product->ingredients()->sync($syncData);
        }

        $this->command->info('✅ 8 Products & Resep berhasil di-seed.');

        // ──────────────────────────────────────────────
        // 4. PURCHASE ORDERS
        // ──────────────────────────────────────────────
        $purchaseOrders = [
            // PO 1 - Order biji kopi dari PT Kopi Nusantara Jaya (COMPLETED)
            [
                'supplier'      => $supplierModels[0],
                'status'        => 'completed',
                'order_date'    => Carbon::now()->subDays(15),
                'received_date' => Carbon::now()->subDays(10),
                'items'         => [
                    ['sku' => 'BB-KOPI-ARB', 'quantity' => 2000.00, 'quantity_received' => 2000.00, 'unit_price' => 120.00],
                    ['sku' => 'BB-KOPI-ROB', 'quantity' => 1500.00, 'quantity_received' => 1500.00, 'unit_price' => 85.00],
                ],
            ],
            // PO 2 - Order susu dari CV Susu Murni Sejahtera (COMPLETED)
            [
                'supplier'      => $supplierModels[1],
                'status'        => 'completed',
                'order_date'    => Carbon::now()->subDays(10),
                'received_date' => Carbon::now()->subDays(7),
                'items'         => [
                    ['sku' => 'BB-SUSU-FC',  'quantity' => 5000.00, 'quantity_received' => 5000.00, 'unit_price' => 18.00],
                    ['sku' => 'BB-SUSU-OAT', 'quantity' => 3000.00, 'quantity_received' => 3000.00, 'unit_price' => 35.00],
                ],
            ],
            // PO 3 - Order gula & cokelat dari UD Gula Manis Sentosa (ON_DELIVERY)
            [
                'supplier'      => $supplierModels[2],
                'status'        => 'on_delivery',
                'order_date'    => Carbon::now()->subDays(5),
                'received_date' => null,
                'items'         => [
                    ['sku' => 'BB-GULA-ARN',  'quantity' => 2000.00, 'quantity_received' => 1500.00, 'unit_price' => 25.00],
                    ['sku' => 'BB-GULA-PTH',  'quantity' => 3000.00, 'quantity_received' => 3000.00, 'unit_price' => 12.00],
                    ['sku' => 'BB-CKLAT-BK',  'quantity' => 1000.00, 'quantity_received' => 0.00,    'unit_price' => 95.00],
                ],
            ],
            // PO 4 - Order tepung dari PT Sumber Bahan Bakery (SENT)
            [
                'supplier'      => $supplierModels[3],
                'status'        => 'sent',
                'order_date'    => Carbon::now()->subDays(2),
                'received_date' => null,
                'items'         => [
                    ['sku' => 'BB-TEPUNG-PT', 'quantity' => 5000.00, 'quantity_received' => 0.00, 'unit_price' => 15.00],
                    ['sku' => 'BB-MATCHA',    'quantity' => 500.00,  'quantity_received' => 0.00, 'unit_price' => 250.00],
                ],
            ],
            // PO 5 - Order kopi lagi dari PT Kopi Nusantara Jaya (DRAFT)
            [
                'supplier'      => $supplierModels[0],
                'status'        => 'draft',
                'order_date'    => Carbon::now(),
                'received_date' => null,
                'items'         => [
                    ['sku' => 'BB-KOPI-ARB', 'quantity' => 3000.00, 'quantity_received' => 0.00, 'unit_price' => 118.00],
                    ['sku' => 'BB-KOPI-ROB', 'quantity' => 2000.00, 'quantity_received' => 0.00, 'unit_price' => 82.00],
                    ['sku' => 'BB-ES-BATU',  'quantity' => 10000.00,'quantity_received' => 0.00, 'unit_price' => 2.00],
                ],
            ],
        ];

        foreach ($purchaseOrders as $poData) {
            // Calculate total
            $totalAmount = 0;
            foreach ($poData['items'] as $item) {
                $totalAmount += $item['quantity'] * $item['unit_price'];
            }

            $po = PurchaseOrder::create([
                'supplier_id'   => $poData['supplier']->id,
                'status'        => $poData['status'],
                'total_amount'  => $totalAmount,
                'order_date'    => $poData['order_date'],
                'received_date' => $poData['received_date'],
            ]);

            // Sync ingredients
            $syncData = [];
            foreach ($poData['items'] as $item) {
                $ingredient = $ingredientModels[$item['sku']];
                $syncData[$ingredient->id] = [
                    'quantity'          => $item['quantity'],
                    'quantity_received' => $item['quantity_received'],
                    'unit_price'        => $item['unit_price'],
                ];
            }
            $po->ingredients()->sync($syncData);
        }

        $this->command->info('✅ 5 Purchase Orders berhasil di-seed.');
        $this->command->info('');
        $this->command->info('🎉 Semua dummy data berhasil ditambahkan!');
    }
}
