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
        Schema::create('tp', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('link', 500)->nullable();
            $table->string('status', 50)->default('pending'); // pending, validated, rejected
            $table->text('admin_comment')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index('user_id');
            $table->index('status');
            
            // Clé étrangère (si la table users existe)
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp');
    }
};
