<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->text('reason');
            $table->json('evidence_images')->nullable();
            $table->string('status')->default('pending');
            $table->decimal('refund_amount', 12, 2)->nullable();
            $table->foreignId('processed_by_staff_id')->nullable()->constrained('staffs');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_requests');
    }
};
