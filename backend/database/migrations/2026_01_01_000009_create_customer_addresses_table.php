<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->string('province');
            $table->string('district');
            $table->string('ward');
            $table->string('detail_address');
            $table->boolean('is_default')->default(false);
            $table->unsignedBigInteger('customer_id');

            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
