<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('promo_codes', 'code')) {
            DB::statement('ALTER TABLE promo_codes MODIFY code VARCHAR(255) NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('promo_codes', 'code')) {
            DB::statement('ALTER TABLE promo_codes MODIFY code VARCHAR(255) NOT NULL');
        }
    }
};
