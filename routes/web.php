<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Central / Public Routes
|--------------------------------------------------------------------------
|
| Rutas del dominio central (lavadofacil.test, lavadofacil.tu-app.co).
| Filament SuperAdmin se monta automáticamente en /central por CentralPanelProvider.
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware(['web', 'throttle:6,1'])
    ->name('contact.store');
