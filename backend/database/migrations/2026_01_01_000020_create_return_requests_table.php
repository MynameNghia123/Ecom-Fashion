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
            $table->string('ticket_code')->unique(); // #RET-XXXX
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('order_detail_id')->nullable()->constrained('order_details')->nullOnDelete();
            // Lý do trả: defective | wrong_size | wrong_item | change_mind | other
            $table->string('reason');
            $table->text('customer_note')->nullable();
            $table->json('evidence_images')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('refund_amount', 12, 2)->nullable();
            // Status flow: pending → approved → received → refunded | rejected
            $table->string('status')->default('pending');
            $table->text('admin_note')->nullable();
            $table->foreignId('processed_by_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_requests');
    }
};
