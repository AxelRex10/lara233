<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\category;

class categoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        category::create([
            'categoria1' => 'Algo',
            'categoria2' => 'Gastronomia',
            'categoria3' => 'Verduras',
            'categoria4' => 'Caceria',
            'categoria5' => 'Empleo',
            'categoria6' => 'Baratos',
            'categoria7' => 'Naturaleza'
        ]);
    }
}
