<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reco60_enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->string('brand_name');
            $table->string('project_type', 40)->default('marque'); // marque | entreprise | activite | projet_personnel
            $table->text('presentation');
            $table->string('platforms')->nullable();
            $table->text('objectives')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status', 20)->default('in_progress'); // in_progress | completed | cancelled
            $table->timestamps();

            $table->index('user_id');
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reco60_enrollments');
    }
};
