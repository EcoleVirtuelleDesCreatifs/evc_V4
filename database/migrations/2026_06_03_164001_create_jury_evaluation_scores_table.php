<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jury_evaluation_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jury_evaluation_id')->constrained('jury_evaluations')->onDelete('cascade');
            $table->string('category_key');
            $table->string('category_label');
            $table->string('criterion_key');
            $table->string('criterion_label');
            $table->unsignedTinyInteger('score')->default(0);
            $table->unsignedTinyInteger('max_score')->default(20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jury_evaluation_scores');
    }
};
