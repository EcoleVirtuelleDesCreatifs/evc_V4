<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_registrations', 'commercial_admin_id')) {
                $column = $table->unsignedBigInteger('commercial_admin_id')->nullable()->index();
                if (Schema::hasColumn('pre_registrations', 'reviewed_by')) {
                    $column->after('reviewed_by');
                } elseif (Schema::hasColumn('pre_registrations', 'status')) {
                    $column->after('status');
                }
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'commercial_admin_id')) {
                $table->unsignedBigInteger('commercial_admin_id')->nullable()->index()->after('user_id');
            }
        });

        Schema::table('pre_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('pre_registrations', 'commercial_admin_id')) {
                $table->foreign('commercial_admin_id')->references('id')->on('admins')->nullOnDelete();
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'commercial_admin_id')) {
                $table->foreign('commercial_admin_id')->references('id')->on('admins')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'commercial_admin_id')) {
                $table->dropForeign(['commercial_admin_id']);
                $table->dropColumn('commercial_admin_id');
            }
        });

        Schema::table('pre_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('pre_registrations', 'commercial_admin_id')) {
                $table->dropForeign(['commercial_admin_id']);
                $table->dropColumn('commercial_admin_id');
            }
        });
    }
};
