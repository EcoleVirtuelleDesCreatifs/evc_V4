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
        Schema::create('seances', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('formation'); // Nom de la formation cible (ex: 'Design Graphique')
            $table->enum('type', ['presentiel', 'online'])->default('presentiel');
            $table->string('location')->nullable(); // Lieu pour présentiel
            $table->text('meet_link')->nullable(); // Lien Google Meet pour online
            $table->dateTime('scheduled_at');
            $table->unsignedSmallInteger('duration_minutes')->default(60);
            $table->enum('status', ['planned', 'completed', 'cancelled'])->default('planned');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();

            $table->index('formation');
            $table->index(['scheduled_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seances');
    }
};
