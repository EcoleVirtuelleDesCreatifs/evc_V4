<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->date('date_naissance')->nullable()->after('age');
            $table->string('sexe', 1)->nullable()->after('date_naissance');
            $table->string('nationalite')->nullable()->after('sexe');
            $table->string('ville')->nullable()->after('pays');
            $table->string('domaine_etude')->nullable()->after('niveau_etude');
            $table->text('competences')->nullable()->after('domaine_etude');
            $table->string('programme')->nullable()->after('niveau_dans_formation');
            $table->string('how_known')->nullable()->after('programme');
            $table->boolean('certify')->default(false)->after('how_known');
            $table->boolean('consent')->default(false)->after('certify');
        });
    }

    public function down(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'date_naissance',
                'sexe',
                'nationalite',
                'ville',
                'domaine_etude',
                'competences',
                'programme',
                'how_known',
                'certify',
                'consent',
            ]);
        });
    }
};
