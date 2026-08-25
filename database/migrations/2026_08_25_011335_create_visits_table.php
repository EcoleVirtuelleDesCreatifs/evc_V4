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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->ipAddress('ip')->nullable();
            $table->string('path')->nullable();
            $table->text('user_agent')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->timestamp('visited_at')->useCurrent();
            $table->timestamps();

            $table->index('visited_at');
            $table->index(['product_id', 'visited_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
