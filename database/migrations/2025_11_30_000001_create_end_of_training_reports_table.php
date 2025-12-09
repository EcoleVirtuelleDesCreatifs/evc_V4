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
        Schema::create('end_of_training_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('student_id');
            $table->string('formation', 100); // Design Graphique, Community Management, etc.
            $table->string('file_path'); // Chemin du fichier PDF
            $table->string('original_filename'); // Nom original du fichier
            $table->integer('file_size')->nullable(); // Taille en octets
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_comment')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable(); // ID de l'admin qui a validé
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index('user_id');
            $table->index('student_id');
            $table->index('status');
            $table->index('formation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('end_of_training_reports');
    }
};
