<?php

use App\Http\Controllers\SchoolController;
use App\Models\City;
use App\Models\District;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/schools/{school}/pdf', [
    SchoolController::class,
    'exportPdf'
])->name('schools.pdf');

Route::resource('schools', SchoolController::class);

Route::get('states/{state}/districts', function ($state)
{
    return District::where('state_id', $state)->orderBy('name')->get(['id', 'name']);

})->name('states.districts');

Route::get('/districts/{district}/cities', function ($district)
{
    return City::where('district_id', $district)->orderBy('name')->get(['id', 'name']);

})->name('districts.cities');
