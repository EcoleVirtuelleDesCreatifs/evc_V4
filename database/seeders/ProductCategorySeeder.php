<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Livres', 'description' => 'Livres et guides de formation'],
            ['name' => 'Goodies', 'description' => 'Articles promotionnels EVC'],
            ['name' => 'Templates', 'description' => 'Templates et ressources digitales'],
            ['name' => 'Formations', 'description' => 'Formations et cours en ligne'],
        ];

        foreach ($categories as $category) {
            ProductCategory::firstOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
