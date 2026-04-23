<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\category;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Verduras', 'description' => 'Productos frescos de huerto'],
            ['name' => 'Frutas', 'description' => 'Frutas de temporada'],
            ['name' => 'Lacteos', 'description' => 'Leche, queso y derivados'],
            ['name' => 'Carnes', 'description' => 'Res, pollo y cerdo'],
            ['name' => 'Panaderia', 'description' => 'Panes y reposteria'],
            ['name' => 'Bebidas', 'description' => 'Jugos, refrescos y mas'],
            ['name' => 'Limpieza', 'description' => 'Productos para el hogar'],
        ];

        foreach ($categories as $categoryData) {
            $category = category::create($categoryData);

            Product::factory(30)->create([
                'idcategory' => $category->id,
            ]);
        }
    }
}
