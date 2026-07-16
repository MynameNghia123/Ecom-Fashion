<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value');
            $table->string('data_type');
            $table->string('description')->nullable();
            $table->foreignId('updated_by_staff_id')->nullable()->constrained('staffs');
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
