<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiJobcenterController;
use App\Http\Controllers\Api\ApiArbeitsagenturController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'prefix'    => 'jobcenter',
], function () {
    Route::get('locations', [ApiJobcenterController::class, 'locations']);
    Route::get('count', [ApiJobcenterController::class, 'count']);
    Route::get('scrape/{postcode}', [ApiJobcenterController::class, 'scrape']);
    Route::patch('found/{zipcode}', [ApiJobcenterController::class, 'setFounded']);
});
Route::group([
    'prefix'    => 'arbeitsagentur',
], function () {
    Route::get('locations', [ApiArbeitsagenturController::class, 'locations']);
    Route::get('count', [ApiArbeitsagenturController::class, 'count']);
	Route::get('scrape/{postcode}', [ApiArbeitsagenturController::class, 'scrape']);
    Route::patch('found/{zipcode}', [ApiArbeitsagenturController::class, 'setFounded']);
});
