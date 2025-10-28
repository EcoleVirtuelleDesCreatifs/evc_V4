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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->longText('content');
            
            // Event details
            $table->date('event_date');
            $table->date('event_end_date')->nullable();
            $table->string('location')->nullable();
            $table->enum('event_type', ['online', 'physical', 'hybrid'])->default('physical');
            $table->string('registration_link')->nullable();
            
            // Media
            $table->string('cover_image')->nullable();
            $table->string('cover_image_alt')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            
            // Visibility & Status
            $table->enum('visibility', ['all', 'specific'])->default('all');
            $table->json('formations')->nullable(); // Array of formation IDs
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->boolean('is_featured')->default(false);
            
            // Publishing
            $table->timestamp('published_at')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('admins')->onDelete('set null');
            
            // Analytics
            $table->integer('views_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index('event_date');
            $table->index('is_featured');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
