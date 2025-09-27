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
        Schema::create('document_validations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('document_type'); // cv, motivation, pressbook, rapport, realisation
            $table->string('document_name');
            $table->string('document_path');
            $table->enum('status', ['en_cours', 'valide', 'rejete'])->default('en_cours');
            $table->text('admin_comment')->nullable(); // Commentaire de l'admin
            $table->unsignedBigInteger('validated_by')->nullable(); // ID de l'admin qui a validé
            $table->timestamp('validated_at')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['user_id', 'document_type']);
            $table->index(['status']);
            $table->index(['validated_at']);
            
            // Contrainte unique pour éviter les doublons
            $table->unique(['user_id', 'document_type', 'document_name'], 'unique_user_document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_validations');
    }
};
