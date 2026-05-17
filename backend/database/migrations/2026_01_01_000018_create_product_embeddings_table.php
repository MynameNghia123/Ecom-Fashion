<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_embeddings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->unique();
            $table->text('embedding_vector');
            $table->string('model_used')->nullable();
            $table->integer('tokens_used')->default(0);
            $table->dateTime('last_updated')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_embeddings');
    }
};
