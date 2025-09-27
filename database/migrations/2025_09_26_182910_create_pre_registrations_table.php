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
        Schema::create('pre_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->integer('age')->nullable();
            $table->string('photo')->nullable();
            $table->string('email')->unique();
            $table->string('whatsapp')->nullable();
            $table->string('pays');
            $table->string('niveau_etude');
            $table->enum('choix_formation', ['design_graphique', 'community_management', 'gestion_informatique', 'intelligence_artificielle']);
            $table->boolean('has_computer')->default(false);
            $table->boolean('has_smartphone')->default(false);
            $table->string('disponibilite');
            $table->text('motivation');
            $table->string('status')->default('pending'); // pending, accepted, rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_registrations');
    }
};
