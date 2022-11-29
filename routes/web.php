<?php

use App\Jobs\SleepJob;
use App\Models\Charger;
use App\Models\Location;
use App\Models\LocationUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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
    return view('dashboard');
})->name('home');

Route::get('/reports', function () {
    return view('reports');
})->name('reports');


Route::get('/user/{user}/favorites/', function (User $user) {
    return view('user.favorites', [
        'user' => $user,
        'locations' => $user->locations,
    ]);
})->name('user.favorites');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
