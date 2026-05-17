<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('role_id');

            $table->primary(['staff_id', 'role_id']);
            $table->foreign('staff_id')->references('id')->on('staff')->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_roles');
    }
};
