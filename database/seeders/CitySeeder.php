<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        City::updateOrCreate(
            ['name' => 'New York'],
            ['country_id' => 1, 'lat' => 40.712776, 'long' => -74.005974]
        );

        City::updateOrCreate(
            ['name' => 'London'],
            ['country_id' => 2, 'lat' => 51.507351, 'long' => -0.127758]
        );

        City::updateOrCreate(
            ['name' => 'São João de Meriti'],
            ['country_id' => 3, 'lat' => -22.80389, 'long' => -43.37222]
        );
    }
}
