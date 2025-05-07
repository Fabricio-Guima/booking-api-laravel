<?php
namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Room;
use App\Models\Bed;

use Illuminate\Database\Seeder;

class RoomAndBedSeeder extends Seeder
{
    public function run(): void
    {
        // Exemplo para o primeiro apartamento
        $apartment1 = Apartment::where('name', 'Small apartment')->first();

        $bedroom = Room::create([
            'apartment_id' => $apartment1->id,
            'room_type_id' => 1, // Bedroom
            'name' => 'Quarto Principal',
        ]);

        Bed::create([
            'room_id' => $bedroom->id,
            'bed_type_id' => 1, // Single bed
            'name' => 'Cama de solteiro',
        ]);

        // Exemplo para o segundo apartamento
        $apartment2 = Apartment::where('name', 'Large apartment')->first();

        $room1 = Room::create([
            'apartment_id' => $apartment2->id,
            'room_type_id' => 1, // Bedroom
            'name' => 'Suíte Casal',
        ]);

        $room2 = Room::create([
            'apartment_id' => $apartment2->id,
            'room_type_id' => 2, // Living room
            'name' => 'Sala de estar',
        ]);

        Bed::create([
            'room_id' => $room1->id,
            'bed_type_id' => 2, // Large double bed
            'name' => 'Cama Queen',
        ]);

        Bed::create([
            'room_id' => $room2->id,
            'bed_type_id' => 4, // Sofa bed
            'name' => 'Sofá-cama',
        ]);
    }
}
