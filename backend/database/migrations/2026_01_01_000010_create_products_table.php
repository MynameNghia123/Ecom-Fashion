<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('deleted_at')->nullable();
            $table->string('brand')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->string('thumbnail')->nullable();
            $table->unsignedBigInteger('created_by_staff')->nullable();
            $table->unsignedBigInteger('updated_by_staff')->nullable();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('created_by_staff')->references('id')->on('staff')->nullOnDelete();
            $table->foreign('updated_by_staff')->references('id')->on('staff')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
