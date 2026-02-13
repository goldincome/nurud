<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $json = File::get(database_path('data/country_codes.json'));
        $countries = json_decode($json, true);

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['iso_alpha2' => $country['A2']],
                [
                    'name'         => $country['COUNTRY'],
                    //'iso_alpha2'   => $country['A2'] ?? null, // Handle potential missing A2 code
                    'iso_alpha3'   => $country['A3'],
                    'numeric_code' => $country['NUM'],
                    'dialing_code' => $country['DIALINGCODE'],
                ]
            );
        }
    }
}
