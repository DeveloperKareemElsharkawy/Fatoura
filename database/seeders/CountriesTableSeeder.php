<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('countries')->insert([
            'name' => 'Egypt',
            'code' => 'EG',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert Saudi Arabia
        DB::table('countries')->insert([
            'name' => 'Saudi Arabia',
            'code' => 'SA',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
