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
        Schema::create('design_project_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('original_name', 255);
            $table->string('file_path', 500);
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->string('file_type', 50)->nullable(); // image, document, video, etc.
            $table->boolean('is_thumbnail')->default(false);
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index('project_id');
            $table->index('file_type');
            
            // Clé étrangère
            // $table->foreign('project_id')->references('id')->on('design_projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_project_files');
    }
};
