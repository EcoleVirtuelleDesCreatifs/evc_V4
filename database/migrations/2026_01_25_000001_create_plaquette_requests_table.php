<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plaquette_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('plaquette_id')->constrained('plaquettes')->cascadeOnDelete();

            $table->string('nom', 120);
            $table->string('prenoms', 120);
            $table->string('type_formation', 120);
            $table->string('pays', 120);
            $table->string('ville', 120);
            $table->string('whatsapp', 40);
            $table->string('email', 255);
            $table->string('niveau_etude', 120);
            $table->text('motivation');

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->unsignedBigInteger('approved_by_admin_id')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('admin_comment')->nullable();

            $table->timestamps();

            $table->index(['status', 'plaquette_id']);
            $table->index(['email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plaquette_requests');
    }
};
