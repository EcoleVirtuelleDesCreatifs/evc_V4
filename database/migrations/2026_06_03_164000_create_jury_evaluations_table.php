<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jury_evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('jury_name');
            $table->string('jury_function')->nullable();
            $table->string('jury_email');
            $table->date('evaluation_date');
            $table->string('group_name');
            $table->text('global_comment')->nullable();
            $table->unsignedSmallInteger('total_score')->default(0);
            $table->enum('status', ['draft', 'submitted'])->default('submitted');
            $table->timestamps();

            $table->unique(['jury_email', 'group_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jury_evaluations');
    }
};
