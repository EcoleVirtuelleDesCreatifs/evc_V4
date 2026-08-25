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
            $table->string('promo_code')->nullable()->after('total');
            $table->integer('discount')->default(0)->after('promo_code');
            $table->integer('delivery_cost')->default(0)->after('discount');
            $table->integer('subtotal')->default(0)->after('delivery_cost');
            $table->integer('final_total')->default(0)->after('subtotal');
            $table->index('promo_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_orders', function (Blueprint $table) {
            $table->dropColumn(['promo_code', 'discount', 'delivery_cost', 'subtotal', 'final_total']);
        });
    }
};
