<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\Standard;
use App\Models\State;
use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationAndStandardSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            'Maharashtra' => [
                'Mumbai' => ['Mumbai', 'Thane', 'Navi Mumbai'],
                'Pune' => ['Pune', 'Pimpri-Chinchwad'],
                'Nashik' => ['Nashik'],
            ],
            'Gujarat' => [
                'Ahmedabad' => ['Ahmedabad'],
                'Surat' => ['Surat'],
            ],
            'Goa' => [
                'North Goa' => ['Panaji', 'Mapusa'],
                'South Goa' => ['Margao'],
            ],
            'Karnataka' => [
                'Bengaluru Urban' => ['Bengaluru'],
                'Mysuru' => ['Mysuru'],
            ],
            'Madhya Pradesh' => [
                'Bhopal' => ['Bhopal'],
                'Indore' => ['Indore'],
            ],
        ];

        foreach ($locations as $stateName => $districts) {
            $state = State::where('name', $stateName)->firstOrFail();

            foreach ($districts as $districtName => $cities) {
                $district = District::firstOrCreate([
                    'state_id' => $state->id,
                    'name' => $districtName,
                ]);

                foreach ($cities as $cityName) {
                    City::firstOrCreate([
                        'district_id' => $district->id,
                        'name' => $cityName,
                    ]);
                }
            }
        }

        foreach (range(1, 12) as $number) {
            Standard::firstOrCreate([
                'name' => 'Standard ' . $number,
            ]);
        }
    }
}
