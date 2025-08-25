<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilityPropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $facilities = Facility::whereNull('category_id')->pluck('id');

        if ($facilities->isEmpty()) {
            $this->command->warn('Nenhuma facility sem categoria encontrada.');
            return;
        }

        $propertyIds = [1, 2];

        $properties = Property::whereIn('id', $propertyIds)->get();

        if ($properties->isEmpty()) {
            $this->command->warn('Nenhuma property encontrada para vincular.');
            return;
        }

        foreach ($properties as $property) {
            $property->facilities()->syncWithoutDetaching($facilities);
        }


    }
}
