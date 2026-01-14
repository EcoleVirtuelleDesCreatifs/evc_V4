<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('admin_admin_job_profiles')) {
            Schema::create('admin_admin_job_profiles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('admin_id')->index();
                $table->unsignedBigInteger('job_profile_id')->index();
                $table->date('starts_at')->nullable();
                $table->date('ends_at')->nullable();
                $table->unsignedTinyInteger('allocation_percent')->default(100);
                $table->timestamps();

                $table->unique(['admin_id', 'job_profile_id', 'starts_at'], 'u_admin_job_profiles');

                $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
                $table->foreign('job_profile_id')->references('id')->on('admin_job_profiles')->onDelete('cascade');
            });
            return;
        }

        // Si la table existe déjà (tentative précédente), on ajoute seulement les contraintes manquantes.
        Schema::table('admin_admin_job_profiles', function (Blueprint $table) {
            $table->unique(['admin_id', 'job_profile_id', 'starts_at'], 'u_admin_job_profiles');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('job_profile_id')->references('id')->on('admin_job_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_admin_job_profiles');
    }
};
