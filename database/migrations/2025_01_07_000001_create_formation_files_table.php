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
        Schema::create('formation_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('formation_id');
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('file_path');
            $table->bigInteger('file_size'); // Taille en bytes
            $table->string('mime_type');
            $table->enum('file_type', ['pdf', 'document', 'image', 'video', 'other'])->default('pdf');
            $table->timestamps();

            // Clé étrangère
            $table->foreign('formation_id')
                  ->references('id')
                  ->on('formations')
                  ->onDelete('cascade');

            // Index
            $table->index('formation_id');
            $table->index('file_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formation_files');
    }
};
