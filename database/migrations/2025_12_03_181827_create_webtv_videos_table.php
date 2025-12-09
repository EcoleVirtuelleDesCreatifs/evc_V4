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
        Schema::create('webtv_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url');
            $table->string('thumbnail')->nullable();
            $table->enum('type', ['normal', 'live'])->default('normal');
            $table->enum('status', ['scheduled', 'active', 'paused', 'ended'])->default('scheduled');
            $table->dateTime('scheduled_start')->nullable();
            $table->dateTime('scheduled_end')->nullable();
            $table->dateTime('actual_start')->nullable();
            $table->dateTime('actual_end')->nullable();
            $table->boolean('loop_enabled')->default(true);
            $table->integer('loop_count')->default(0); // Nombre de fois que la vidéo a bouclé
            $table->integer('view_count')->default(0);
            $table->integer('order')->default(0); // Ordre de lecture dans la playlist
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Paramètres supplémentaires (volume, autoplay, etc.)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webtv_videos');
    }
};
