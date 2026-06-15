<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');                              // Nama bahan baku
            $table->string('sku')->unique();                     // Kode unik bahan baku
            $table->decimal('stock', 8, 2)->default(0);          // Stok saat ini
            $table->string('unit');                               // Satuan (gram, ml, pcs, kg, liter)
            $table->decimal('safety_stock', 8, 2)->default(0);   // Batas minimum stok untuk alert
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
