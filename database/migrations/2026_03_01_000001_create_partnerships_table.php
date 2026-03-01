<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partnerships', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('prefix')->default('Partenaire à');
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->string('document_path')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        DB::table('partnerships')->insert([
            'slug' => 'onp',
            'prefix' => 'Partenaire à',
            'name' => 'ONP',
            'subtitle' => '(Ministère du Plan et du Développement)',
            'document_path' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('partnerships');
    }
};
