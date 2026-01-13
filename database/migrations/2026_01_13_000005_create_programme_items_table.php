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
        Schema::create('programme_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('programme_id')->index();

            $table->string('thematique', 255);
            $table->date('session_date');
            $table->time('session_time');
            $table->string('type_formation', 50); // en_ligne | presentielle
            $table->string('lieu', 255)->nullable();
            $table->text('description')->nullable();

            $table->string('piece_jointe', 500)->nullable();
            $table->string('piece_jointe_mime', 120)->nullable();

            $table->timestamps();

            $table->foreign('programme_id')
                ->references('id')
                ->on('programmes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programme_items');
    }
};
