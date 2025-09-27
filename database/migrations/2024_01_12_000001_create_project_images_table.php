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
        Schema::create('project_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('filename'); // Nom du fichier stocké
            $table->string('original_name'); // Nom original du fichier
            $table->string('mime_type');
            $table->bigInteger('file_size'); // Taille en bytes
            $table->string('file_path'); // Chemin relatif du fichier
            $table->boolean('is_thumbnail')->default(false); // Image principale
            $table->integer('order_index')->default(0); // Ordre d'affichage
            $table->timestamps();
            
            // Clé étrangère
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            
            // Index pour les requêtes
            $table->index(['project_id', 'order_index']);
            $table->index(['project_id', 'is_thumbnail']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_images');
    }
};
