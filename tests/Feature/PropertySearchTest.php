<?php

namespace Tests\Feature;

use App\Models\Apartment;
use App\Models\Bed;
use App\Models\BedType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\City;
use App\Models\Country;
use App\Models\Geoobject;
use App\Models\Property;
use App\Models\Role;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;

class PropertySearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_search_by_city_returns_correct_results(): void
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_OWNER]);

        $city1 = City::factory()->create(['id' => 100, 'country_id' => 1]);
        $city2 = City::factory()->create(['id' => 200, 'country_id' => 1]);

        $propertyInCity = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $city1->id,
        ]);

        $propertyInAnotherCity = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $city2->id,
        ]);

        $response = $this->getJson('/api/search?city_id=' . $city1->id);

        $response->assertStatus(200);

        // Apenas conte quantas propriedades retornam com o ID que você espera
        $this->assertCount(1, collect($response->json())->where('id', $propertyInCity->id));
    }



    public function test_property_search_by_country_returns_correct_results(): void
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_OWNER]);
        $countries = Country::with('cities')->take(2)->get();
        $propertyInCountry = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $countries[0]->cities()->value('id')
        ]);
        $propertyInAnotherCountry = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $countries[1]->cities()->value('id')
        ]);

        $response = $this->getJson('/api/search?country_id=' . $countries[0]->id);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['id' => $propertyInCountry->id]);
    }

    public function test_property_search_by_geoobject_returns_correct_results(): void
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_OWNER]);
        $cityId = City::value('id');
        $geoobject = Geoobject::first();
        $propertyNear = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
            'lat' => $geoobject->lat,
            'long' => $geoobject->long,
        ]);
        $propertyFar = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
            'lat' => $geoobject->lat + 10,
            'long' => $geoobject->long - 10,
        ]);

        $response = $this->getJson('/api/search?geoobject=' . $geoobject->id);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['id' => $propertyNear->id]);
    }

    public function test_property_search_by_capacity_returns_correct_results(): void
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_OWNER]);
        $cityId = City::value('id');
        $propertyWithSmallApartment = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);
        Apartment::factory()->create([
            'property_id' => $propertyWithSmallApartment->id,
            'capacity_adults' => 1,
            'capacity_children' => 0,
        ]);
        $propertyWithLargeApartment = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);
        Apartment::factory()->create([
            'property_id' => $propertyWithLargeApartment->id,
            'capacity_adults' => 3,
            'capacity_children' => 2,
        ]);

        $response = $this->getJson('/api/search?city=' . $cityId . '&adults=2&children=1');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['id' => $propertyWithLargeApartment->id]);
    }

    public function test_property_search_by_capacity_returns_only_suitable_apartments(): void
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_OWNER]);
        $cityId = City::value('id');
        $property = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);
        $smallApartment = Apartment::factory()->create([
            'name' => 'Small apartment',
            'property_id' => $property->id,
            'capacity_adults' => 1,
            'capacity_children' => 0,
        ]);
        $largeApartment = Apartment::factory()->create([
            'name' => 'Large apartment',
            'property_id' => $property->id,
            'capacity_adults' => 3,
            'capacity_children' => 2,
        ]);

        $response = $this->getJson('/api/search?city=' . $cityId . '&adults=2&children=1');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonCount(1, '0.apartments');
        $response->assertJsonPath('0.apartments.0.name', $largeApartment->name);
    }

    public function test_property_search_beds_list_all_cases(): void
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_OWNER]);
        $cityId = City::value('id');
        $roomTypes = RoomType::all();
        $bedTypes = BedType::all();

        $property = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);
        $apartment = Apartment::factory()->create([
            'name' => 'Small apartment',
            'property_id' => $property->id,
            'capacity_adults' => 1,
            'capacity_children' => 0,
        ]);

        // ----------------------
        // FIRST: check that bed list if empty if no beds
        // ----------------------

        $response = $this->getJson('/api/search?city=' . $cityId);
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonCount(1, '0.apartments');
        $response->assertJsonPath('0.apartments.0.beds_list', '');

        // ----------------------
        // SECOND: create 1 room with 1 bed
        // ----------------------

        $room = Room::create([
            'apartment_id' => $apartment->id,
            'room_type_id' => $roomTypes[0]->id,
            'name' => 'Bedroom',
        ]);
        Bed::create([
            'room_id' => $room->id,
            'bed_type_id' => $bedTypes[0]->id,
        ]);

        $response = $this->getJson('/api/search?city=' . $cityId);
        $response->assertStatus(200);
        $response->assertJsonPath('0.apartments.0.beds_list', '1 ' . $bedTypes[0]->name);

        // ----------------------
        // THIRD: add another bed to the same room
        // ----------------------

        Bed::create([
            'room_id' => $room->id,
            'bed_type_id' => $bedTypes[0]->id,
        ]);
        $response = $this->getJson('/api/search?city=' . $cityId);
        $response->assertStatus(200);
        $response->assertJsonPath('0.apartments.0.beds_list', '2 ' . str($bedTypes[0]->name)->plural());

        // ----------------------
        // FOURTH: add a second room with no beds
        // ----------------------

        $secondRoom = Room::create([
            'apartment_id' => $apartment->id,
            'room_type_id' => $roomTypes[0]->id,
            'name' => 'Living room',
        ]);
        $response = $this->getJson('/api/search?city=' . $cityId);
        $response->assertStatus(200);
        $response->assertJsonPath('0.apartments.0.beds_list', '2 ' . str($bedTypes[0]->name)->plural());

        // ----------------------
        // FIFTH: add one bed to that second room
        // ----------------------

        Bed::create([
            'room_id' => $secondRoom->id,
            'bed_type_id' => $bedTypes[0]->id,
        ]);
        $response = $this->getJson('/api/search?city=' . $cityId);
        $response->assertStatus(200);
        $response->assertJsonPath('0.apartments.0.beds_list', '3 ' . str($bedTypes[0]->name)->plural());

        // ----------------------
        // SIXTH: add another bed with a different type to that second room
        // ----------------------

        Bed::create([
            'room_id' => $secondRoom->id,
            'bed_type_id' => $bedTypes[1]->id,
        ]);
        $response = $this->getJson('/api/search?city=' . $cityId);
        $response->assertStatus(200);
        $response->assertJsonPath('0.apartments.0.beds_list', '4 beds (3 ' . str($bedTypes[0]->name)->plural() . ', 1 ' . $bedTypes[1]->name . ')');

        // ----------------------
        // SEVENTH: add a second bed with that new type to that second room
        // ----------------------

        Bed::create([
            'room_id' => $secondRoom->id,
            'bed_type_id' => $bedTypes[1]->id,
        ]);
        $response = $this->getJson('/api/search?city=' . $cityId);
        $response->assertStatus(200);
        $response->assertJsonPath('0.apartments.0.beds_list', '5 beds (3 ' . str($bedTypes[0]->name)->plural() . ', 2 ' . str($bedTypes[1]->name)->plural() . ')');
    }

    public function test_property_search_returns_one_best_apartment_per_property()
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_OWNER]);
        $cityId = City::value('id');
        $property = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);
        $largeApartment = Apartment::factory()->create([
            'name' => 'Large apartment',
            'property_id' => $property->id,
            'capacity_adults' => 3,
            'capacity_children' => 2,
        ]);
        $midSizeApartment = Apartment::factory()->create([
            'name' => 'Mid size apartment',
            'property_id' => $property->id,
            'capacity_adults' => 2,
            'capacity_children' => 1,
        ]);
        $smallApartment = Apartment::factory()->create([
            'name' => 'Small apartment',
            'property_id' => $property->id,
            'capacity_adults' => 1,
            'capacity_children' => 0,
        ]);

        $response = $this->getJson('/api/search?city=' . $cityId . '&adults=2&children=1');

        $response->assertStatus(200);
        $response->assertJsonCount(1, '0.apartments');
        $response->assertJsonPath('0.apartments.0.name', $midSizeApartment->name);
    }
}
