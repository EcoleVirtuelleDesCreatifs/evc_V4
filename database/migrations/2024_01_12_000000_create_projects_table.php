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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('category');
            $table->text('description')->nullable();
            $table->string('link')->nullable();
            $table->text('tags')->nullable();
            $table->json('software_used'); // Stockage des logiciels utilisés en JSON
            $table->string('thumbnail_image')->nullable(); // Image principale/miniature
            $table->enum('status', ['en_cours', 'termine', 'valide', 'rejete'])->default('en_cours');
            $table->timestamps();
            
            // Index pour les requêtes fréquentes
            $table->index(['user_id', 'status']);
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
