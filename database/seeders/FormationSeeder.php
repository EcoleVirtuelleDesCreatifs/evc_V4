<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FormationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $formations = [
            ['nom' => 'Formation Complète Design Graphique', 'domaine' => 'Design', 'email' => 'contact@evc.com', 'niveau' => 'Tous'],
            ['nom' => 'Devenir Community Manager en 2025', 'domaine' => 'Marketing', 'email' => 'contact@evc.com', 'niveau' => 'Tous'],
        ];

        foreach ($formations as $formation) {
            DB::table('formations')->insert([
                'nom' => $formation['nom'],
                'domaine' => $formation['domaine'],
                'email' => $formation['email'],
                'niveau' => $formation['niveau'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
