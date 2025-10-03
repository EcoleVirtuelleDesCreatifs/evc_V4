<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                if (!Schema::hasColumn('students', 'whatsapp')) {
                    $table->string('whatsapp', 50)->nullable()->after('phone');
                }
                if (!Schema::hasColumn('students', 'years_experience')) {
                    $table->integer('years_experience')->nullable()->after('credits_earned');
                }
                if (!Schema::hasColumn('students', 'industry_sector')) {
                    $table->string('industry_sector', 255)->nullable()->after('years_experience');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                if (Schema::hasColumn('students', 'industry_sector')) {
                    $table->dropColumn('industry_sector');
                }
                if (Schema::hasColumn('students', 'years_experience')) {
                    $table->dropColumn('years_experience');
                }
                if (Schema::hasColumn('students', 'whatsapp')) {
                    $table->dropColumn('whatsapp');
                }
            });
        }
    }
};
