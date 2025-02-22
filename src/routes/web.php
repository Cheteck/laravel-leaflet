<?php

use Illuminate\Support\Facades\Route;
use IJIDeals\Laraleaflet\Http\Controllers\MapController;

Route::get('/map', [MapController::class, 'index']);
Route::get('/geocode', [MapController::class, 'geocode']);
Route::get('/route', [MapController::class, 'calculateRoute']);
