<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (!Schema::hasColumn('admins', 'can_view_salary_amount')) {
                $table->boolean('can_view_salary_amount')->default(false)->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (Schema::hasColumn('admins', 'can_view_salary_amount')) {
                $table->dropColumn('can_view_salary_amount');
            }
        });
    }
};
