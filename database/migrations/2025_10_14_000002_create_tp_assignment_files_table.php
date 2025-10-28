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
        Schema::create('tp_assignment_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tp_assignment_id');
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->string('file_type', 50)->nullable(); // image, pdf, etc.
            $table->integer('file_size')->nullable(); // en bytes
            $table->timestamps();
            
            // Index
            $table->index('tp_assignment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_assignment_files');
    }
};
