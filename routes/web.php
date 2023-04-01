<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobcenterController;
use App\Http\Controllers\ArbeitsagenturController;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group([
    'prefix'    => 'admin',
    'as'        => 'admin.',
    'middleware' => ['auth'],
], function() {
    Route::get('jobcenters/crawle', [JobcenterController::class, 'crawle'])->name('jobcenters.crawle');
    Route::resource('jobcenters', JobcenterController::class);
    Route::post('jobcenters', [JobcenterController::class, 'search'])->name('jobcenters.search');

    Route::get('arbeitsagenturen/crawle', [ArbeitsagenturController::class, 'crawle'])->name('arbeitsagenturen.crawle');
    Route::resource('arbeitsagenturen', ArbeitsagenturController::class);
    Route::post('arbeitsagenturen', [ArbeitsagenturController::class, 'search'])->name('arbeitsagenturen.search');
});
Route::get('phpinfo', fn() => phpinfo());
require __DIR__.'/auth.php';
