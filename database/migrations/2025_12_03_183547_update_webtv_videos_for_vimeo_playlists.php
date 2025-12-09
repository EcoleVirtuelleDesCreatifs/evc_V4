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
            // Champs Vimeo
            $table->string('vimeo_playlist_id')->nullable()->after('video_url');
            $table->string('vimeo_showcase_id')->nullable()->after('vimeo_playlist_id');
            $table->string('vimeo_user_id')->nullable()->after('vimeo_showcase_id');
            $table->integer('total_videos')->default(0)->after('vimeo_user_id');
            $table->json('playlist_data')->nullable()->after('total_videos'); // Données complètes de la playlist
            $table->string('embed_code')->nullable()->after('playlist_data'); // Code iframe Vimeo
            $table->boolean('autoplay')->default(true)->after('loop_enabled');
            $table->boolean('autopause')->default(true)->after('autoplay');
            $table->integer('current_video_index')->default(0)->after('loop_count'); // Index de la vidéo en cours
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webtv_videos', function (Blueprint $table) {
            $table->dropColumn([
                'vimeo_playlist_id',
                'vimeo_showcase_id',
                'vimeo_user_id',
                'total_videos',
                'playlist_data',
                'embed_code',
                'autoplay',
                'autopause',
                'current_video_index',
            ]);
        });
    }
};
