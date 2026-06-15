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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // Nama supplier/toko
            $table->string('phone');                        // Nomor kontak/WhatsApp
            $table->text('address');                        // Alamat
            $table->integer('payment_terms')->default(0);   // Tempo pembayaran (hari), 0 = COD
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
