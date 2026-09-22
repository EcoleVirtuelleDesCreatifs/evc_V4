<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slot_id')->constrained('appointment_slots')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->string('motif');
            $table->text('message')->nullable();
            $table->string('status', 20)->default('pending'); // pending | confirmed | cancelled | completed
            $table->string('meet_link')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['slot_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
