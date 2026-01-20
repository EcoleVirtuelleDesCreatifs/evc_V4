<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plaquettes', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();

            $table->foreignId('formation_id')->nullable()->constrained('formations')->nullOnDelete();

            $table->string('file_path');
            $table->string('original_filename');
            $table->unsignedBigInteger('file_size')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->enum('format', ['online', 'offline'])->default('online');

            $table->unsignedBigInteger('download_count')->default(0);

            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['is_published', 'is_active', 'formation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plaquettes');
    }
};
