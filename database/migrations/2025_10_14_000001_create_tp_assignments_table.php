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
        Schema::create('tp_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('student_id');
            $table->string('title', 255);
            $table->text('description');
            $table->dateTime('deadline');
            $table->string('formation', 100);
            $table->string('status', 50)->default('assigned'); // assigned, submitted, validated, rejected
            $table->unsignedBigInteger('assigned_by'); // ID de l'admin qui a assigné
            $table->text('submission_link')->nullable(); // Lien de soumission par l'étudiant
            $table->text('admin_comment')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index('user_id');
            $table->index('student_id');
            $table->index('status');
            $table->index('formation');
            $table->index('assigned_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_assignments');
    }
};
