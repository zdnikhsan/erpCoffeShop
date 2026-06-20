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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number')->unique();        // EXP-YYYYMM-0001
            $table->string('category');                         // Gaji, Listrik & Air, Sewa Tempat, Maintenance, Lainnya
            $table->decimal('amount', 12, 2);                   // Nominal pengeluaran
            $table->text('note')->nullable();                   // Keterangan detail
            $table->date('date');                               // Tanggal pengeluaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
