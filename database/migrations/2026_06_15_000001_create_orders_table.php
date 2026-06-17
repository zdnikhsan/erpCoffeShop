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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->enum('order_type', ['dine_in', 'takeaway', 'delivery']);
            $table->string('table_number')->nullable();
            $table->string('payment_method');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_pay', 12, 2);
            $table->foreignId('cashier_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
