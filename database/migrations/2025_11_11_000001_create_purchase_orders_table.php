<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('product_name');
            $table->string('po_number')->unique();
            $table->string('sku_number');
            $table->unsignedInteger('quantity');
            $table->decimal('product_price', 12, 2);
            $table->date('delivery_date');
            $table->string('status')->default('pending'); // pending, in_transit, delivered
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};