<?php

namespace Database\Seeders;

use App\Models\Apartment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Apartment::create([
            'property_id' => 1,
            'name' => 'Small apartment',
            'capacity_adults' => 1,
            'capacity_children' => 0,
        ]);

        Apartment::create([
            'property_id' => 1,
            'name' => 'Large apartment',
            'capacity_adults' => 3,
            'capacity_children' => 2,
        ]);
    }
}
