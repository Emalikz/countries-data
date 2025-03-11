<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PostalCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Str;

class PostalCodeSeeder extends Seeder
{
    public function run()
    {
        $filePath = database_path('data/postal_codes.txt');

        if (!File::exists($filePath)) {
            return;
        }

        $handle = fopen($filePath, "r");
        $batch = [];
        while (($line = fgetcsv($handle, 10000, "\t")) !== false) {
            $city_id = $this->getCityId(\Illuminate\Support\Str::lower($line[2]));
            if($city_id === null) {
                dump(\Illuminate\Support\Str::lower($line[2]), 'not found');
                continue;
            }
            $batch[] = [
                'code' => $line[1],  // Segundo campo: código postal
                'city_id' => $city_id
            ];

            if(count($batch) === 500) {
                PostalCode::insert($batch);
                $batch = [];
            }
            
        }
        if (!empty($batch)) {
            PostalCode::insert($batch);
        }

        fclose($handle);
    }

    private function getCityId($cityName)
    {
        return DB::table('cities')
            ->where('name_ci', $cityName)
            ->value('id');
    }
}