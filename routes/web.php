<?php

use App\Http\Controllers\AddSeriesController;
use App\Http\Controllers\EpisodesController;
use App\Http\Controllers\SeriesController;
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


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/', function () {
        return view('dashboard');
    })->name('welcome');
    Route::get('/series', [SeriesController::class, 'main'])->name('series');
    Route::get('/episodes', [EpisodesController::class, 'main'])->name('episodes');
    Route::post('/add-series', [AddSeriesController::class, 'main'])->name('add-series');
});
