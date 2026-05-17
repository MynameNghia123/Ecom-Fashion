<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('discount_value', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->string('type'); // e.g., 'percentage', 'fixed'
            $table->decimal('price_min_order_value', 12, 2)->default(0);
            $table->integer('max_usage')->nullable();
            $table->integer('used_count')->default(0);
            $table->dateTime('expiry_date');
            $table->unsignedBigInteger('created_by_staff_id')->nullable();

            $table->foreign('created_by_staff_id')->references('id')->on('staff')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
