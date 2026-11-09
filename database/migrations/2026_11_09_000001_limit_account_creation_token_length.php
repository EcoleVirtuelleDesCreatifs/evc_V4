<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * TEXT + index unique n'est pas supporté par MySQL 8 (seul MariaDB l'accepte via USING HASH) :
     * on aligne la colonne sur varchar(512), comme dans database/schema/mysql-schema.sql.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('payments', 'account_creation_token')
            || Schema::getColumnType('payments', 'account_creation_token') === 'varchar') {
            return;
        }

        $maxLength = (int) DB::table('payments')->max(DB::raw('CHAR_LENGTH(account_creation_token)'));
        if ($maxLength > 512) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            $table->string('account_creation_token', 512)->nullable()->change();
        });
    }

    public function down(): void
    {
        // MySQL refuse un index unique sur TEXT sans préfixe : on ne revient en arrière que sur MariaDB.
        if (! method_exists(DB::connection(), 'isMaria') || ! DB::connection()->isMaria()) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            $table->text('account_creation_token')->nullable()->change();
        });
    }
};
