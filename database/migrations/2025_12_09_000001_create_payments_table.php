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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->unsignedBigInteger('pre_registration_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            // Infos paiement
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('XOF');
            $table->enum('payment_method', ['orange_money', 'mtn_mobile', 'wave', 'moov_money', 'carte_bancaire', 'cinetpay'])->nullable();

            // Status & tracking
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->string('transaction_id')->unique()->nullable();
            $table->string('payment_reference')->unique();

            // CinetPay specifique
            $table->string('cpm_trans_id')->nullable()->comment('CinetPay transaction ID');
            $table->string('cpm_site_id')->nullable();
            $table->text('cpm_custom')->nullable()->comment('Custom data from CinetPay');

            // Données du payeur
            $table->string('phone_number', 20)->nullable();
            $table->string('payer_name')->nullable();
            $table->string('payer_email')->nullable();

            // Token pour création de compte
            $table->string('account_creation_token', 64)->nullable()->unique();
            $table->boolean('account_created')->default(false);
            $table->timestamp('account_created_at')->nullable();

            // Dates
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable()->comment('Lien expire après 7 jours');
            $table->timestamps();

            // Index
            $table->index('pre_registration_id');
            $table->index('student_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('transaction_id');
            $table->index('cpm_trans_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
