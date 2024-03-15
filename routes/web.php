<?php

use App\Models\User;
use App\Livewire\Map;
use App\Jobs\SleepJob;
use App\Models\Charger;
use App\Models\Company;
use App\Models\Location;
use App\Models\LocationUser;
use App\Livewire\Report\Page;
use App\Livewire\FeedbackList;
use App\Livewire\Location\Index;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Profile\LocationRadiusForm;

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

Route::get('/', Index::class)->name('home');

Route::get('radius', LocationRadiusForm::class)
    ->name('radius')
    ->middleware('auth');

Route::get('feedback', FeedbackList::class)
    ->name('feedback');

Route::get('chart/{location}', function (Location $location) {
    return view('chart', [
        'location' => $location,
    ]);
})->name('location.chart');

Route::get('map', function () {
    return view('map');
})->name('map');

Route::get('log/{filename}', function ($filename) {
    return json_decode(Storage::disk('local')->get('clever/' . $filename));
});

Route::get('logs', function () {
    return Storage::disk('local')->files('clever');
});

Route::get('/reports', Page::class)->name('reports');

Route::get('/ajson', function () {
    return [
            'clever' => [
                'DK*CLE*E11600*1' => [
                    'evseId' => 'DK*CLE*E11600*1',
                    'locationId' => 'd0698aec-3d8d-eb11-b1ac-0022489bc085',
                    'status' => Charger::OCCUPIED,
                ],
                'DK*CLE*E11600*2' => [
                    'evseId' => 'DK*CLE*E11600*2',
                    'locationId' => 'd0698aec-3d8d-eb11-b1ac-0022489bc085',
                    'status' => Charger::AVAILABLE,
                ],
                'DK*CLE*E11601*1' => [
                    'evseId' => 'DK*CLE*E11601*1',
                    'locationId' => 'd0698aec-3d8d-eb11-b1ac-0022489bc085',
                    'status' => Charger::OCCUPIED,
                ],
                'DK*CLE*E11601*2' => [
                    'evseId' => 'DK*CLE*E11601*2',
                    'locationId' => 'd0698aec-3d8d-eb11-b1ac-0022489bc085',
                    'status' => Charger::OCCUPIED,
                ],
            ]
        ];
})->name('ajson');


Route::get('ljson', function () {
    return [
        'clever' => [
            'd0698aec-3d8d-eb11-b1ac-0022489bc085' => [
                "name" => "Dampfærgevej 2",
                "origin" => "Clever",
                'publicAccess' => [
                    'isRoamingAllowed' => true,
                    'visibility' => 'PUBLIC',
                ],
                'address' => [
                    'address' => 'Dampfærgevej 2',
                    'city' => 'København',
                    'countryCode' => 'DK',
                    'postalCode' => '2100'
                ],
                'coordinates' => [
                    'lat' => 55.686,
                    'lng' => 12.568
                ],
                'evses' => [
                    'DK*CLE*E11600*1' => [
                        'chargePointId' => '11600',
                        'connectors' => [
                            'DK*CLE*E11600*1-1' => [
                                "balance" => "None",
                                "connectionType" => "Socket",
                                "connectorId" => "1",
                                "evseConnectorId" => "DK*CLE*E11600*1-1",
                                "maxCurrentAmp" => "16",
                                "maxPowerKw" => "11.04",
                                "plugType" => "Type2",
                                "powerType" => "AC3Phase",
                                "speed" => "Standard"
                            ]
                            ],
                        "evseId" => "DK*CLE*E11600*1",
                        ],
                    'DK*CLE*E11600*2' => [
                        'chargePointId' => '11600',
                        'connectors' => [
                            'DK*CLE*E11600*2-2' => [
                                "balance" => "None",
                                "connectionType" => "Socket",
                                "connectorId" => "1",
                                "evseConnectorId" => "DK*CLE*E11600*2-2",
                                "maxCurrentAmp" => "16",
                                "maxPowerKw" => "11.04",
                                "plugType" => "Type2",
                                "powerType" => "AC3Phase",
                                "speed" => "Standard"
                            ]
                            ],
                        "evseId" => "DK*CLE*E11600*2",
                    ],
                ]
            ]
        ]
    ];
})->name('ljson');

Route::post('/app-check', function () {
    $clever = Company::where('name', 'Clever')->first();
    $validated = request()->validate([
        'app_check_token' => ['required', 'string', 'min:100']
    ]);
    $clever->update($validated);

    return response()->json([
        'message' => 'Token updated',
    ]);
})->name('app-check');


Route::get('/user/{user}/favorites/', Index::class)
    ->name('user.favorites');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/home', function () {
        return view('dashboard');
    })->name('dashboard');
});
