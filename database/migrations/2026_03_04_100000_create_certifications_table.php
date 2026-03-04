<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('formation')->nullable(); // ex: 'Design Graphique', 'Community Management', etc.
            $table->integer('duration_minutes')->default(60);
            $table->decimal('passing_score', 5, 2)->default(50.00); // note de passage en %
            $table->decimal('total_points', 8, 2)->default(100);
            $table->boolean('is_active')->default(false);
            $table->boolean('shuffle_questions')->default(false);
            $table->text('instructions')->nullable(); // consignes avant le test
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('certification_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certification_id')->constrained('certifications')->onDelete('cascade');
            $table->enum('type', ['qcm', 'redaction'])->default('qcm');
            $table->text('question_text');
            $table->string('media_url')->nullable(); // image ou fichier joint
            $table->decimal('points', 5, 2)->default(1);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        Schema::create('certification_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('certification_questions')->onDelete('cascade');
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        Schema::create('certification_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certification_id')->constrained('certifications')->onDelete('cascade');
            $table->unsignedBigInteger('student_id');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->decimal('score', 8, 2)->nullable();
            $table->decimal('score_percentage', 5, 2)->nullable();
            $table->enum('status', ['not_started', 'in_progress', 'submitted', 'graded'])->default('not_started');
            $table->boolean('is_auto_submitted')->default(false);
            $table->boolean('passed')->nullable();
            $table->text('admin_feedback')->nullable();
            $table->timestamps();

            $table->unique(['certification_id', 'student_id']);
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });

        Schema::create('certification_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('certification_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('certification_questions')->onDelete('cascade');
            $table->unsignedBigInteger('selected_option_id')->nullable(); // pour QCM
            $table->text('answer_text')->nullable(); // pour rédaction
            $table->boolean('is_correct')->nullable(); // auto-calculé pour QCM
            $table->decimal('score', 5, 2)->nullable(); // noté par admin pour rédaction
            $table->text('admin_comment')->nullable();
            $table->timestamps();

            $table->foreign('selected_option_id')->references('id')->on('certification_options')->onDelete('set null');
            $table->unique(['attempt_id', 'question_id']);
        });

        Schema::create('certification_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certification_id')->constrained('certifications')->onDelete('cascade');
            $table->unsignedBigInteger('student_id');
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            $table->unique(['certification_id', 'student_id']);
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certification_student');
        Schema::dropIfExists('certification_answers');
        Schema::dropIfExists('certification_attempts');
        Schema::dropIfExists('certification_options');
        Schema::dropIfExists('certification_questions');
        Schema::dropIfExists('certifications');
    }
};
