<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(PermissionSeeder::class);

        $this->call(CountrySeeder::class);
        $this->call(CitySeeder::class);
        $this->call(PropertySeeder::class);
        $this->call(GeoobjectSeeder::class);
        $this->call(ApartmentSeeder::class);
        // apartment_type is created in ApartmentType migration
        $this->call(RoomAndBedSeeder::class);
        $this->call(FacilityCategorySeeder::class);
        $this->call(FacilitySeeder::class);
    }
}
