<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'alimentation', 'color' => '#ff7f50'],
            ['name' => 'assurance', 'color' => '#da70d6'],
            ['name' => 'loisir', 'color' => '#ffdab9'],
            ['name' => 'transport', 'color' => '#6495ed'],
            ['name' => 'autre', 'color' => '#d2b48c'],
            ['name' => 'impôt', 'color' => '#daa520'],
            ['name' => 'voiture', 'color' => '#00ff00'],
        ]);
    }
}
