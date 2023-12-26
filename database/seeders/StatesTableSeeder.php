<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch the IDs of Egypt and Saudi Arabia from the countries table
        $egyptId = DB::table('countries')->where('name', 'Egypt')->value('id');
        $saudiArabiaId = DB::table('countries')->where('name', 'Saudi Arabia')->value('id');

        // States for Egypt
        $egyptGovernorates = [
            ['country_id' => $egyptId, 'name' => 'Cairo'],
            ['country_id' => $egyptId, 'name' => 'Alexandria'],
            ['country_id' => $egyptId, 'name' => 'Aswan'],
            ['country_id' => $egyptId, 'name' => 'Asyut'],
            ['country_id' => $egyptId, 'name' => 'Beheira'],
            ['country_id' => $egyptId, 'name' => 'Beni Suef'],
            ['country_id' => $egyptId, 'name' => 'Dakahlia'],
            ['country_id' => $egyptId, 'name' => 'Damietta'],
            ['country_id' => $egyptId, 'name' => 'Faiyum'],
            ['country_id' => $egyptId, 'name' => 'Gharbia'],
            ['country_id' => $egyptId, 'name' => 'Giza'],
            ['country_id' => $egyptId, 'name' => 'Ismailia'],
            ['country_id' => $egyptId, 'name' => 'Kafr El Sheikh'],
            ['country_id' => $egyptId, 'name' => 'Luxor'],
            ['country_id' => $egyptId, 'name' => 'Matrouh'],
            ['country_id' => $egyptId, 'name' => 'Minya'],
            ['country_id' => $egyptId, 'name' => 'Monufia'],
            ['country_id' => $egyptId, 'name' => 'New Valley'],
            ['country_id' => $egyptId, 'name' => 'North Sinai'],
            ['country_id' => $egyptId, 'name' => 'Port Said'],
            ['country_id' => $egyptId, 'name' => 'Qalyubia'],
            ['country_id' => $egyptId, 'name' => 'Qena'],
            ['country_id' => $egyptId, 'name' => 'Red Sea'],
            ['country_id' => $egyptId, 'name' => 'Sohag'],
            ['country_id' => $egyptId, 'name' => 'South Sinai'],
            ['country_id' => $egyptId, 'name' => 'Suez'],
            ['country_id' => $egyptId, 'name' => '6th of October'],
        ];

        // States for Saudi Arabia
        $saudiArabiaRegions = [
            ['country_id' => $saudiArabiaId, 'name' => 'Riyadh'],
            ['country_id' => $saudiArabiaId, 'name' => 'Makkah'],
            ['country_id' => $saudiArabiaId, 'name' => 'Madinah'],
            ['country_id' => $saudiArabiaId, 'name' => 'Eastern Province'],
            ['country_id' => $saudiArabiaId, 'name' => 'Asir'],
            ['country_id' => $saudiArabiaId, 'name' => 'Tabuk'],
            ['country_id' => $saudiArabiaId, 'name' => 'Hail'],
            ['country_id' => $saudiArabiaId, 'name' => 'Northern Borders'],
            ['country_id' => $saudiArabiaId, 'name' => 'Jizan'],
            ['country_id' => $saudiArabiaId, 'name' => 'Najran'],
            ['country_id' => $saudiArabiaId, 'name' => 'Al Bahah'],
            ['country_id' => $saudiArabiaId, 'name' => 'Al Jawf'],
            ['country_id' => $saudiArabiaId, 'name' => 'Riyadh'],
        ];

        // Insert states into the states table
        DB::table('states')->insert(array_merge($egyptGovernorates, $saudiArabiaRegions));
    }
}
