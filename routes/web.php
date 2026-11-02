<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\FavoritePlaceController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\FeedbackController;
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

    // Login / daftar dengan Google
    Route::get('/auth/google', [GoogleController::class, 'redirect'])
        ->name('google.redirect');

    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
        ->name('google.callback');
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

    Route::get('/favorites/{favoritePlace}/edit', [FavoritePlaceController::class, 'edit'])
        ->name('favorites.edit');

    Route::put('/favorites/{favoritePlace}', [FavoritePlaceController::class, 'update'])
        ->name('favorites.update');

    Route::delete('/favorites/{favoritePlace}', [FavoritePlaceController::class, 'destroy'])
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


    // Teman
    Route::get('/friends', [FriendController::class, 'index'])
        ->name('friends.index');

    Route::get('/friends/{user}', [FriendController::class, 'show'])
        ->name('friends.show');

    Route::get('/friends/{user}/data', [FriendController::class, 'data'])
        ->name('friends.data');

    Route::post('/friends/request/{user}', [FriendController::class, 'store'])
        ->name('friends.store');

    Route::patch('/friends/{friendship}/accept', [FriendController::class, 'accept'])
        ->name('friends.accept');

    Route::delete('/friends/{friendship}', [FriendController::class, 'destroy'])
        ->name('friends.destroy');


    // Laporan & Saran (kirim ke zaykayla575@gmail.com)
    Route::post('/feedback', [FeedbackController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('feedback.store');
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

        // Pengguna (pantau)
        Route::get('/users', [Admin\UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/{user}', [Admin\UserController::class, 'show'])
            ->name('users.show');

        // Daftar role kustom (pantau)
        Route::get('/roles', [Admin\CustomRoleController::class, 'index'])
            ->name('roles.index');

        // Tempat (pantau)
        Route::get('/places', [Admin\PlaceController::class, 'index'])
            ->name('places.index');

        // =====================================================================
        // AKSI KHUSUS OWNER (Admin hanya bisa memantau, tidak bisa ubah/cabut role)
        // =====================================================================
        Route::middleware('owner')->group(function () {
            // Ubah role sistem (Pengguna Biasa, Admin, Owner) - berikan atau cabut admin
            Route::put('/users/{user}/system-role', [Admin\UserRoleController::class, 'updateSystemRole'])
                ->name('users.system-role.update');

            // Atur role kustom milik satu pengguna (boleh banyak)
            Route::put('/users/{user}/roles', [Admin\UserRoleController::class, 'update'])
                ->name('users.roles.update');

            // Buat role kustom baru langsung dari daftar pengguna + otomatis diberikan ke user itu
            Route::post('/users/{user}/roles/create', [Admin\CustomRoleController::class, 'storeAndAssign'])
                ->name('users.roles.create');

            // Kelola daftar role kustom (tambah, edit, hapus)
            Route::post('/roles', [Admin\CustomRoleController::class, 'store'])
                ->name('roles.store');

            Route::put('/roles/{role}', [Admin\CustomRoleController::class, 'update'])
                ->name('roles.update');

            Route::delete('/roles/{role}', [Admin\CustomRoleController::class, 'destroy'])
                ->name('roles.destroy');
        });
    });