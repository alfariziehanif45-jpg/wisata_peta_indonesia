<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserPresence;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /** Daftar semua user + filter status login + pencarian. */
    public function index(Request $request, UserPresence $presence)
    {
        $status = $request->query('status', 'all');   // all | login | offline
        $search = trim((string) $request->query('q', ''));

        $loggedInIds = $presence->loggedInUserIds();

        $users = User::query()
            ->withFriendsCount()
            ->withCount(['favoritePlaces', 'travelPlans'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($w) use ($search) {
                    $w->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->when($status === 'login', fn ($q) => $q->whereIn('id', $loggedInIds))
            ->when($status === 'offline', fn ($q) => $q->whereNotIn('id', $loggedInIds))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $activity = $presence->lastActivity($users->pluck('id'));

        $totals = [
            'all'     => User::count(),
            'login'   => $loggedInIds->count(),
            'offline' => max(User::count() - $loggedInIds->count(), 0),
        ];

        return view('admin.users.index', compact(
            'users', 'activity', 'status', 'search', 'presence', 'totals'
        ));
    }

    /** Detail satu user: favorit, rencana perjalanan, teman. */
    public function show(User $user, UserPresence $presence)
    {
        $user->loadCount([
            'favoritePlaces',
            'travelPlans',
            'sentRequests as sent_friends_count'         => fn ($q) => $q->where('status', 'accepted'),
            'receivedRequests as received_friends_count' => fn ($q) => $q->where('status', 'accepted'),
        ]);

        $favorites = $user->favoritePlaces()->latest()->get();

        $plans = $user->travelPlans()
            ->withCount([
                'places',
                'places as visited_places_count' => fn ($q) => $q->where('visited', true),
            ])
            ->latest()
            ->get();

        $friends = $user->friends()->get();

        $lastActivity = $presence->lastActivity([$user->id])[$user->id] ?? null;
        $status       = $presence->status($lastActivity);

        return view('admin.users.show', compact(
            'user', 'favorites', 'plans', 'friends', 'lastActivity', 'status'
        ));
    }
}