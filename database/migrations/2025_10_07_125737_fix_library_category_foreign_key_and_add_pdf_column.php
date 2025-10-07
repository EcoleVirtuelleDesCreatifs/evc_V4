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
        Schema::table('libraries', function (Blueprint $table) {
            // Supprimer l'ancienne contrainte de clé étrangère si elle existe
            $table->dropForeign('libraries_category_id_foreign');
            
            // Ajouter la bonne contrainte de clé étrangère vers library_categories
            $table->foreign('library_category_id')
                  ->references('id')
                  ->on('library_categories')
                  ->onDelete('set null');
            
            // Ajouter une colonne pour le fichier PDF du livre
            $table->string('pdf_path')->nullable()->after('path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('libraries', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère
            $table->dropForeign(['library_category_id']);
            
            // Recréer l'ancienne contrainte (vers categories)
            $table->foreign('library_category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null');
            
            // Supprimer la colonne pdf_path
            $table->dropColumn('pdf_path');
        });
    }
};
