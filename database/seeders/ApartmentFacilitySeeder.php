<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Facility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apartment = Apartment::where('id', 2)->first();

        if ($apartment) {
            // Pegar as 3 primeiras facilidades
            $facilities = Facility::take(3)->pluck('id');

            // Associar as facilidades ao apartamento
            $apartment->facilities()->sync($facilities);
        }
    }
}
