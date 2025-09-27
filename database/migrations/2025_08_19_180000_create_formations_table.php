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
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('level')->default('beginner'); // beginner, intermediate, advanced
            $table->integer('duration_weeks')->default(4);
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_internal')->default(true); // Formation interne EVC ou externe
            $table->json('target_student_types')->nullable(); // Types d'étudiants ciblés
            $table->enum('student_restriction', ['all', 'active_only', 'registration_period'])->default('all');
            $table->date('registration_start')->nullable(); // Début période d'inscription
            $table->date('registration_end')->nullable(); // Fin période d'inscription
            $table->string('image_url')->nullable();
            $table->json('skills')->nullable(); // Compétences acquises
            $table->json('prerequisites')->nullable(); // Prérequis
            $table->json('modules')->nullable(); // Modules de formation
            $table->enum('status', ['draft', 'active', 'inactive', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->integer('max_students')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('instructor_name')->nullable();
            $table->text('instructor_bio')->nullable();
            $table->json('schedule')->nullable(); // Horaires de formation
            $table->enum('format', ['online', 'offline', 'hybrid'])->default('online');
            $table->string('location')->nullable();
            $table->json('resources')->nullable(); // Ressources pédagogiques
            $table->decimal('satisfaction_rate', 3, 1)->default(0);
            $table->integer('completion_rate')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
