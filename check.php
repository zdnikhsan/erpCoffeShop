<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Clearing tables...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
App\Models\PurchaseOrder::truncate();
App\Models\Supplier::truncate();
App\Models\Ingredient::truncate();
App\Models\Product::truncate();
DB::table('ingredient_product')->truncate();
DB::table('ingredient_purchase_order')->truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');
echo "Clear complete.\n";
