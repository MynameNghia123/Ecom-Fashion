<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained('product_variants');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('cost_price', 12, 2);
            $table->boolean('is_return')->default(false);
            $table->integer('return_quantity')->default(0);
            $table->foreignId('return_request_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
