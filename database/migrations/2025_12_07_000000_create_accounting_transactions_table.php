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
        Schema::create('accounting_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['income', 'expense']); // 'income' = Vente/Recette, 'expense' = Dépense
            $table->string('category'); // Ex: 'Scolarité', 'Salaire', 'Loyer', 'Matériel'
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('reference')->nullable(); // Numéro de facture, etc.
            $table->string('proof_path')->nullable(); // Chemin du justificatif
            $table->string('payment_method')->nullable(); // Virement, Espèces, Chèque, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_transactions');
    }
};
