<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiJobcenterCrawlerController;
use App\Http\Controllers\Api\ApiArbeitsagenturCrawlerController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'prefix'    => 'jobcenter',
], function () {
    Route::get('locations', [ApiJobcenterCrawlerController::class, 'locations']);
    Route::get('count', [ApiJobcenterCrawlerController::class, 'count']);
    Route::get('crawle/{postcode}', [ApiJobcenterCrawlerController::class, 'crawle']);
});
Route::group([
    'prefix'    => 'arbeitsagentur',
], function () {
    Route::get('locations', [ApiArbeitsagenturCrawlerController::class, 'locations']);
    Route::get('count', [ApiArbeitsagenturCrawlerController::class, 'count']);
    Route::get('crawle/{postcode}', [ApiArbeitsagenturCrawlerController::class, 'crawle']);
});
