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
            ['name' => 'Formation Complète Design Graphique', 'category' => 'design-graphique'],
            ['name' => 'Devenir Community Manager en 2025', 'category' => 'marketing-digital'],
        ];

        foreach ($formations as $formation) {
            $categoryId = DB::table('categories')->where('slug', $formation['category'])->value('id');

            if (! $categoryId) {
                continue;
            }

            DB::table('formations')->updateOrInsert(
                ['slug' => Str::slug($formation['name'])],
                [
                    'name' => $formation['name'],
                    'category_id' => $categoryId,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
