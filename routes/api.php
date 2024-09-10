<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\LocationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('locations', [LocationController::class, 'index'])->name('locations.index');
Route::get('locations/{location:external_id}', [LocationController::class, 'show'])->name('locations.show');

Route::post('new-locations', function (Request $request) {
    Artisan::call('clever:locations');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
