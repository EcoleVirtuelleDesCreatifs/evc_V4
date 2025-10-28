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
        Schema::table('students', function (Blueprint $table) {
            // Ajouter le champ age (nullable car optionnel)
            if (!Schema::hasColumn('students', 'age')) {
                $table->integer('age')->nullable()->after('whatsapp');
            }
            
            // Ajouter le champ biography (nullable car optionnel)
            if (!Schema::hasColumn('students', 'biography')) {
                $table->text('biography')->nullable()->after('country');
            }
            
            // Ajouter le champ address (nullable car optionnel)
            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable()->after('city');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'age')) {
                $table->dropColumn('age');
            }
            
            if (Schema::hasColumn('students', 'biography')) {
                $table->dropColumn('biography');
            }
            
            if (Schema::hasColumn('students', 'address')) {
                $table->dropColumn('address');
            }
        });
    }
};
