<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Property::create([
            'name' => 'Minha Propriedade',
            'city_id' => 3,
            'owner_id' => 1,
            'address_street' => 'avenida silveira martins, casa 3, lote 7',
            'address_postcode' => '25585540',
            'lat' => -23.5505,
            'long' => -46.6333,
        ]);
    }
}
