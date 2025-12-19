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
        Schema::table('communiques', function (Blueprint $table) {
            $table->unsignedBigInteger('actualite_id')->nullable()->after('target_audience');
            $table->unsignedBigInteger('evenement_id')->nullable()->after('actualite_id');

            $table->index('actualite_id');
            $table->index('evenement_id');

            $table->foreign('actualite_id')->references('id')->on('actualites')->nullOnDelete();
            $table->foreign('evenement_id')->references('id')->on('evenements')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('communiques', function (Blueprint $table) {
            $table->dropForeign(['actualite_id']);
            $table->dropForeign(['evenement_id']);

            $table->dropIndex(['actualite_id']);
            $table->dropIndex(['evenement_id']);

            $table->dropColumn(['actualite_id', 'evenement_id']);
        });
    }
};
