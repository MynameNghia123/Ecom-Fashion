<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variant_values', function (Blueprint $table) {
            $table->unsignedBigInteger('variant_id');
            $table->unsignedBigInteger('attribute_value_id');
            $table->boolean('primary')->default(false);

            $table->primary(['variant_id', 'attribute_value_id']);
            $table->foreign('variant_id')->references('id')->on('product_variants')->cascadeOnDelete();
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variant_values');
    }
};
