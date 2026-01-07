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
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 255);
            $table->string('fichier_pdf', 500); // Chemin du fichier PDF
            $table->string('formation', 100); // Formation destinataire
            $table->text('description')->nullable();
            $table->json('student_ids')->nullable(); // Ciblage optionnel: liste d'IDs étudiants
            $table->unsignedBigInteger('created_by'); // ID de l'admin qui a créé
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index('formation');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
