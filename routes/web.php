<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;

Route::get(
    '/',
    [MapController::class, 'index']
)->name('map.index');

Route::get(
    '/provinsi/{slug}',
    [MapController::class, 'province']
)->name('map.province');

Route::get(
    '/kota/{slug}',
    [MapController::class, 'city']
)->name('map.city');

Route::get(
    '/api/geocode',
    [MapController::class, 'geocode']
)->name('map.geocode');

Route::get(
    '/api/places',
    [MapController::class, 'places']
)->name('map.places');
