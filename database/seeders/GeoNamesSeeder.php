<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\File;

class GeoNamesSeeder extends Seeder
{
    public function run()
    {
        Country::truncate();
        State::truncate();
        City::truncate();
        
        $json = File::get(database_path('data/new_data.json'));
        $countries = json_decode($json, true);
        $countriesData = collect($countries);


        $countriesData->each(function ($countryData) {
            $country = Country::create([
                'id' => $countryData['id'],
                'name' => $countryData['name'],
                'iso2' => $countryData['iso2'],
                'iso3' => $countryData['iso3'],
                'emoji'=> $countryData['emoji'],
                'phone_code' => $countryData['phonecode']
            ]);

            foreach ($countryData['states'] as $stateData) {
                $state = State::create([
                    'id' => $stateData['id'],
                    'name' => $stateData['name'],
                    'internal_code' => $stateData['internal_code'] ?? null,
                    'iso2' => $stateData['state_code'],
                    'country_id' => $country->id
                ]);

                foreach ($stateData['cities'] as $cityData) {
                    City::create([
                        'id' => $cityData['id'],
                        'postal_code' => $cityData['postal_code'] ?? null,
                        'name' => $cityData['name'],
                        'state_id' => $state->id
                    ]);
                }
            }
        });
    }
}