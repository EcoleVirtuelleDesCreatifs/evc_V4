<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            if (!Schema::hasColumn('programmes', 'image')) {
                $table->string('image', 500)->nullable()->after('titre');
            }
        });
    }

    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
