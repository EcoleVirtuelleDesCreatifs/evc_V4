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
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_type', ['full', 'installment'])->default('full')->after('status');
            $table->integer('installment_number')->nullable()->after('payment_type'); // 1 ou 2
            $table->integer('total_installments')->nullable()->after('installment_number'); // Toujours 2
            $table->decimal('total_amount', 10, 2)->nullable()->after('total_installments'); // Montant total
            $table->unsignedBigInteger('parent_payment_id')->nullable()->after('total_amount'); // Lien vers paiement principal
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'installment_number', 'total_installments', 'total_amount', 'parent_payment_id']);
        });
    }
};
