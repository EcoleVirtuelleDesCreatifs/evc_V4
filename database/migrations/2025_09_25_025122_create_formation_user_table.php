<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('formation_user', function (Blueprint $table) {
            $table->unsignedBigInteger('formation_id');
            $table->unsignedBigInteger('user_id');
            $table->primary(['formation_id', 'user_id']);

            $table->foreign('formation_id')->references('id')->on('formations')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formation_user');
    }
};
