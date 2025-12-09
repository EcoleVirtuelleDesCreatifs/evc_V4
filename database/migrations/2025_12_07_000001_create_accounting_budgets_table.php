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
        Schema::create('accounting_budgets', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->enum('type', ['income', 'expense']);
            $table->integer('year');
            $table->decimal('amount', 15, 2);
            $table->timestamps();

            // Unique constraint to avoid duplicates for same category/year/type
            $table->unique(['category', 'type', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_budgets');
    }
};
