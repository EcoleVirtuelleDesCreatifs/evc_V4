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
        Schema::create('candidatures_formateurs', function (Blueprint $table) {
            $table->id();
            $table->string('prenom');
            $table->string('nom');
            $table->string('email');
            $table->string('telephone', 30);
            $table->string('domaine');
            $table->string('experience', 50);
            $table->text('diplomes');
            $table->text('motivation');
            $table->string('cv_path');
            $table->string('portfolio')->nullable();
            $table->enum('statut', ['nouveau', 'en_cours', 'accepte', 'refuse'])->default('nouveau');
            $table->text('notes_admin')->nullable();
            $table->timestamp('date_traitement')->nullable();
            $table->timestamps();
            
            // Index pour recherche rapide
            $table->index('email');
            $table->index('domaine');
            $table->index('statut');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatures_formateurs');
    }
};
