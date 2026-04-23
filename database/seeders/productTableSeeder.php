<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class productTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Product::create([
            'name' => 'papa',
            'description' => 'Una papa envenenenada',
            'descriptionLong' => ' Una papa muy envenenada',
            'price' => 20, 
            'idcategory' => 1
        ]);

    }
}
