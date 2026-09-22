<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_slots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('mode', 20)->default('en_ligne'); // en_ligne | presentiel
            $table->string('lieu')->nullable(); // lien de réunion ou adresse
            $table->unsignedInteger('capacity')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['date', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_slots');
    }
};
