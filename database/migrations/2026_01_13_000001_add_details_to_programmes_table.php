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
        Schema::table('programmes', function (Blueprint $table) {
            $table->date('programme_date')->nullable()->after('titre');
            $table->time('programme_time')->nullable()->after('programme_date');
            $table->string('type_formation', 50)->nullable()->after('programme_time');
            $table->string('lieu', 255)->nullable()->after('type_formation');
            $table->string('piece_jointe', 500)->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            $table->dropColumn([
                'programme_date',
                'programme_time',
                'type_formation',
                'lieu',
                'piece_jointe',
            ]);
        });
    }
};
