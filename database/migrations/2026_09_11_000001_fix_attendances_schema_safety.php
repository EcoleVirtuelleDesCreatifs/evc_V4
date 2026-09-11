<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Filet de sécurité pour les bases déjà migrées :
 * - attendances.user_id doit être NULLABLE (un étudiant peut ne pas avoir de compte user)
 * - seance_qr_tokens doit exister (si un migrate précédent a échoué en cours de route)
 * - attendances.check_in_at doit exister (pointage QR)
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('attendances')) {
            if (Schema::hasColumn('attendances', 'user_id')) {
                // Rend la colonne nullable même si une contrainte FK existe déjà
                DB::statement('ALTER TABLE `attendances` MODIFY `user_id` BIGINT UNSIGNED NULL');
            }

            if (!Schema::hasColumn('attendances', 'check_in_at')) {
                Schema::table('attendances', function (Blueprint $table) {
                    $table->dateTime('check_in_at')->nullable()->after('recorded_at');
                });
            }
        }

        if (!Schema::hasTable('seance_qr_tokens') && Schema::hasTable('seances')) {
            Schema::create('seance_qr_tokens', function (Blueprint $table) {
                $table->id();
                $table->foreignId('seance_id')->constrained('seances')->onDelete('cascade');
                $table->string('token', 64)->unique();
                $table->dateTime('expires_at');
                $table->dateTime('closed_at')->nullable();
                $table->timestamps();
                $table->index('token');
            });
        }
    }

    public function down(): void
    {
        // No-op : migration de réparation, ne rien casser au rollback
    }
};
