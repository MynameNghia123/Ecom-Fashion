<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_price_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->unsignedBigInteger('updated_by_staff');

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('updated_by_staff')->references('id')->on('staff');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_price_histories');
    }
};
