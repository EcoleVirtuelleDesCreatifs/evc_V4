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
        Schema::table('store_orders', function (Blueprint $table) {
            $table->enum('delivery_mode', ['delivery', 'pickup'])->default('delivery')->after('lieu');
            $table->enum('payment_method', ['cash', 'mobile_money'])->default('cash')->after('delivery_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_mode', 'payment_method']);
        });
    }
};
