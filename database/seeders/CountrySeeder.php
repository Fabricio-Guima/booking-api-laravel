<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Country::updateOrCreate(
            ['name' => 'United States'],
            ['lat' => 37.09024, 'long' => -95.712891]
        );

        Country::updateOrCreate(
            ['name' => 'United Kingdom'],
            ['lat' => 55.378051, 'long' => -3.435973]
        );

        Country::updateOrCreate(
            ['name' => 'Brazil'],
            ['lat' => -14.235004, 'long' => -51.92528]
        );
    }
}
