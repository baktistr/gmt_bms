<?php

use App\BuildingEquipmentHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('test', function () {
    // Collect the last 12 months.
    $months = collect([]);
    // Collect the series data.
    $seriesData = collect([]);

    for ($month = 11; $month >= 0; $month--) {
        $months->push(now()->subMonths($month)->format('M Y'));

        // Get the series data.
        $cost = BuildingEquipmentHistory::query()
            ->where('building_equipment_id', 276)
            ->whereMonth('date_of_problem_fixed', now()->subMonths($month)->format('m'))
            ->whereYear('date_of_problem_fixed', now()->subMonths($month)->format('Y'))
            ->sum('cost');

        $seriesData->push($cost);
    }

    dd($months , $seriesData);
});
