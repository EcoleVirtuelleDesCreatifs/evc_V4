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
        Schema::create('tp_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tp_id');
            $table->string('original_name', 255);
            $table->string('file_path', 500);
            $table->bigInteger('file_size')->nullable(); // Taille en octets
            $table->string('mime_type', 100)->nullable();
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index('tp_id');
            
            // Clé étrangère
            $table->foreign('tp_id')->references('id')->on('tp')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_files');
    }
};
