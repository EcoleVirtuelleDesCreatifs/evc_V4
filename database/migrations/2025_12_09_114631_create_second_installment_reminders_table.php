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
        Schema::create('second_installment_reminders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id'); // ID du paiement 1ère tranche
            $table->string('candidate_email');
            $table->timestamp('sent_at');
            $table->integer('days_remaining')->default(7); // Jours restants après le rappel
            $table->timestamps();

            $table->index('payment_id');
            $table->index('candidate_email');
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('second_installment_reminders');
    }
};
