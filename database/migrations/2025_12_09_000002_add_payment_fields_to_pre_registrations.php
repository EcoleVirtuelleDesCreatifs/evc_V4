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
        Schema::table('pre_registrations', function (Blueprint $table) {
            // Vérifier si la colonne status existe déjà
            if (!Schema::hasColumn('pre_registrations', 'status')) {
                $table->enum('status', ['pending', 'accepted', 'rejected', 'paid', 'activated'])
                    ->default('pending')
                    ->after('id');
            }

            // Colonnes de suivi de validation
            if (!Schema::hasColumn('pre_registrations', 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->nullable()->after('status');
            }

            if (!Schema::hasColumn('pre_registrations', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }

            if (!Schema::hasColumn('pre_registrations', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('reviewed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->dropColumn(['status', 'reviewed_by', 'reviewed_at', 'rejection_reason']);
        });
    }
};
