<?php

namespace Database\Seeders;

use App\Models\State;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            'Maharashtra',
            'Gujarat',
            'Goa',
            'Karnataka',
            'Madhya Pradesh',
        ];

        foreach ($states as $stateName) {
            State::firstOrCreate([
                'name' => $stateName,
            ]);
        }
    }
}
