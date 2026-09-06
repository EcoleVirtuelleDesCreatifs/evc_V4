<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certification_eligibility_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('certification_eligibility_id');
            $table->string('from_system_status', 50)->nullable();
            $table->string('to_system_status', 50)->nullable();
            $table->string('from_admin_status', 50)->nullable();
            $table->string('to_admin_status', 50)->nullable();
            $table->string('from_studio_status', 50)->nullable();
            $table->string('to_studio_status', 50)->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index('certification_eligibility_id');
            $table->index('admin_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certification_eligibility_histories');
    }
};
