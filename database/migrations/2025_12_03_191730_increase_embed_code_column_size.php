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
        Schema::table('webtv_videos', function (Blueprint $table) {
            $table->text('embed_code')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webtv_videos', function (Blueprint $table) {
            $table->string('embed_code')->nullable()->change();
        });
    }
};
