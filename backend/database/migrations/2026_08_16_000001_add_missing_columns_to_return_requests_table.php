<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('return_requests', 'ticket_code')) {
                $table->string('ticket_code')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('return_requests', 'order_detail_id')) {
                $table->foreignId('order_detail_id')->nullable()->after('order_id')->constrained('order_details')->nullOnDelete();
            }
            if (!Schema::hasColumn('return_requests', 'customer_note')) {
                $table->text('customer_note')->nullable()->after('reason');
            }
            if (!Schema::hasColumn('return_requests', 'quantity')) {
                $table->integer('quantity')->default(1)->after('customer_note');
            }
            if (!Schema::hasColumn('return_requests', 'admin_note')) {
                $table->text('admin_note')->nullable()->after('status');
            }
            if (!Schema::hasColumn('return_requests', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('admin_note');
            }
        });
    }

    public function down(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->dropColumn(['ticket_code', 'order_detail_id', 'customer_note', 'quantity', 'admin_note', 'processed_at']);
        });
    }
};
