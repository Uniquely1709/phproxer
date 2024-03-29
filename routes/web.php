<?php

use App\Http\Controllers\AddSeriesController;
use App\Http\Controllers\EpisodesController;
use App\Http\Controllers\LogsController;
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
])->group(function (): void {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/', fn () => view('dashboard'))->name('welcome');
    Route::get('/series', [SeriesController::class, 'main'])->name('series');
    Route::get('/episodes', [EpisodesController::class, 'main'])->name('episodes');
    Route::get('/logs', [LogsController::class, 'main'])->name('logs');
    Route::post('/add-series', [AddSeriesController::class, 'main'])->name('add-series');
});
