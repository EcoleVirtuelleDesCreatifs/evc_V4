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
        Schema::create('tp_submission_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tp_assignment_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('tp_assignment_id');
            
            // Clé étrangère (optionnelle, décommentez si vous voulez la contrainte)
            // $table->foreign('tp_assignment_id')->references('id')->on('tp_assignments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_submission_files');
    }
};
