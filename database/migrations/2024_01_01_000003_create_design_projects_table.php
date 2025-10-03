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
        Schema::create('design_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('category', 50)->default('solo'); // solo, groupe
            $table->string('project_type', 100)->nullable(); // digital, print, branding, etc.
            $table->json('software_used')->nullable(); // Liste des logiciels utilisés
            $table->string('status', 50)->default('pending'); // pending, in_progress, completed, validated, rejected
            $table->text('admin_comment')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index('user_id');
            $table->index('category');
            $table->index('status');
            $table->index('project_type');
            
            // Clé étrangère (si la table users existe)
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_projects');
    }
};
