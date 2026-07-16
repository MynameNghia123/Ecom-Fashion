<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type');
            $table->decimal('discount_value', 12, 2);
            $table->decimal('price_min_order_value', 12, 2)->default(0);
            $table->integer('max_usage');
            $table->integer('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('expiry_date');
            $table->foreignId('created_by_staff_id')->constrained('staffs');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
