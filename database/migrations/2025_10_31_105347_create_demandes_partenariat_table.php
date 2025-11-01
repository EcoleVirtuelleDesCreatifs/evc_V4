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
        Schema::create('demandes_partenariat', function (Blueprint $table) {
            $table->id();
            $table->string('organisation');
            $table->string('nom_contact');
            $table->string('email');
            $table->string('telephone', 30);
            $table->string('site_web')->nullable();
            $table->string('type_partenariat');
            $table->string('secteur');
            $table->text('message');
            $table->enum('statut', ['nouveau', 'en_cours', 'accepte', 'refuse'])->default('nouveau');
            $table->text('notes_admin')->nullable();
            $table->timestamp('date_traitement')->nullable();
            $table->timestamps();
            
            // Index pour recherche rapide
            $table->index('email');
            $table->index('statut');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_partenariat');
    }
};
