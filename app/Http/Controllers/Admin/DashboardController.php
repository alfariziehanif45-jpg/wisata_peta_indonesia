<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FavoritePlace;
use App\Models\Friendship;
use App\Models\TravelPlan;
use App\Models\User;
use App\Services\UserPresence;

class DashboardController extends Controller
{
    public function index(UserPresence $presence)
    {
        $stats = [
            'users'       => User::count(),
            'logged_in'   => $presence->loggedInUserIds()->count(),
            'online'      => $presence->onlineUserIds()->count(),
            'new_users'   => User::where('created_at', '>=', now()->subDays(7))->count(),
            'favorites'   => FavoritePlace::count(),
            'trips'       => TravelPlan::count(),
            'trips_done'  => TravelPlan::whereNotNull('completed_at')->count(),
            'friendships' => Friendship::where('status', 'accepted')->count(),
        ];

        $topFavorites = FavoritePlace::query()
            ->selectRaw('place_key, MAX(name) as name, COUNT(*) as total')
            ->groupBy('place_key')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $latestUsers = User::latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'topFavorites', 'latestUsers'));
    }
}