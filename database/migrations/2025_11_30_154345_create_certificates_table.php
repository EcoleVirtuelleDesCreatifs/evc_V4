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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('user_id');
            $table->string('formation');
            $table->unsignedBigInteger('generated_by')->nullable()->comment('Admin qui a généré le certificat');
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            // Index pour recherche rapide
            $table->index('student_id');
            $table->index('user_id');
            $table->index('formation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
