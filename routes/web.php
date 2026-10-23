<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\FavoritePlaceController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login.store');

    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:10,1')
        ->name('register.store');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Peta Wisata
|--------------------------------------------------------------------------
*/

Route::get('/', [MapController::class, 'index'])
    ->name('map.index');

Route::get('/provinsi/{slug}', [MapController::class, 'province'])
    ->name('map.province');

Route::get('/kota/{slug}', [MapController::class, 'city'])
    ->name('map.city');


/*
|--------------------------------------------------------------------------
| API Peta
|--------------------------------------------------------------------------
*/

Route::get('/api/geocode', [MapController::class, 'geocode'])
    ->name('map.geocode');

Route::get('/api/places', [MapController::class, 'places'])
    ->name('map.places');

Route::get('/api/countries', [MapController::class, 'countries'])
    ->name('map.countries');

Route::get('/api/countries/{country}/provinces', [MapController::class, 'provinces'])
    ->name('map.provinces');

Route::get('/api/provinces/{province}/cities', [MapController::class, 'cities'])
    ->name('map.cities');


/*
|--------------------------------------------------------------------------
| Fitur Pengguna yang Sudah Login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');


    // Favorit dari tombol hati di popup peta
    // (didaftarkan SEBELUM /favorites/{id} agar tidak bentrok)
    Route::get('/favorite-places/status', [FavoritePlaceController::class, 'status'])
        ->name('favorite-places.status');

    Route::post('/favorite-places/toggle', [FavoritePlaceController::class, 'toggle'])
        ->name('favorite-places.toggle');


    // Favorit manual
    Route::get('/favorites', [FavoritePlaceController::class, 'index'])
        ->name('favorites.index');

    Route::get('/favorites/create', [FavoritePlaceController::class, 'create'])
        ->name('favorites.create');

    Route::post('/favorites', [FavoritePlaceController::class, 'store'])
        ->name('favorites.store');

    Route::get('/favorites/{id}/edit', [FavoritePlaceController::class, 'edit'])
        ->name('favorites.edit');

    Route::put('/favorites/{id}', [FavoritePlaceController::class, 'update'])
        ->name('favorites.update');

    Route::delete('/favorites/{id}', [FavoritePlaceController::class, 'destroy'])
        ->name('favorites.destroy');


    // Rencana perjalanan
    Route::get('/trips', [TravelController::class, 'trips'])
        ->name('trips.index');

    Route::post('/trips', [TravelController::class, 'storeTrip'])
        ->name('trips.store');

    Route::post('/trips/{trip}/places', [TravelController::class, 'addTripPlace'])
        ->name('trips.places.store');

    Route::patch('/trips/{trip}/places/{place}/visited', [TravelController::class, 'toggleVisited'])
        ->name('trips.places.toggle');

    Route::post('/trips/{trip}/complete', [TravelController::class, 'completeTrip'])
        ->name('trips.complete');

    Route::delete('/trips/{trip}', [TravelController::class, 'destroyTrip'])
        ->name('trips.destroy');


    // Pencapaian
    Route::get('/achievements', [TravelController::class, 'achievements'])
        ->name('achievements.index');


    // Teman: cari username/nama, lihat profil, kirim, terima, tolak/batalkan, hapus teman
    Route::get('/friends', [FriendController::class, 'index'])
        ->name('friends.index');

    // Lihat profil teman (hanya baca). Memakai {user} sehingga tidak bentrok
    // dengan route PATCH/DELETE {friendship} di bawah (beda method & segmen).
    Route::get('/friends/{user}', [FriendController::class, 'show'])
        ->name('friends.show');

    // Data profil teman terbaru (JSON) untuk sinkronisasi otomatis di halaman profil teman.
    Route::get('/friends/{user}/data', [FriendController::class, 'data'])
        ->name('friends.data');

    Route::post('/friends/request/{user}', [FriendController::class, 'store'])
        ->name('friends.store');

    Route::patch('/friends/{friendship}/accept', [FriendController::class, 'accept'])
        ->name('friends.accept');

    Route::delete('/friends/{friendship}', [FriendController::class, 'destroy'])
        ->name('friends.destroy');
});


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/users', [Admin\UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/{user}', [Admin\UserController::class, 'show'])
            ->name('users.show');

        Route::get('/places', [Admin\PlaceController::class, 'index'])
            ->name('places.index');
    });