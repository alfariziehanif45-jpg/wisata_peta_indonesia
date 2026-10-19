<?php

namespace App\Http\Controllers;

use App\Models\FavoritePlace;
use App\Models\TravelPlan;
use App\Models\TravelPlanPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TravelController extends Controller
{
    public function favorites()
    {
        $favorites = FavoritePlace::where('user_id', auth()->id())->latest()->get();
        return view('favorites.index', compact('favorites'));
    }

    public function storeFavorite(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'url' => ['nullable', 'url', 'max:2000'],
            'place_key' => ['nullable', 'string', 'max:64'],
        ]);
        $data['place_key'] = $data['place_key'] ?: hash('sha256', Str::lower(trim($data['name'].'|'.($data['address'] ?? ''))));
        FavoritePlace::firstOrCreate(
            ['user_id' => auth()->id(), 'place_key' => $data['place_key']],
            ['name' => $data['name'], 'address' => $data['address'] ?? null, 'url' => $data['url'] ?? null]
        );
        return back()->with('success', 'Wisata ditambahkan ke favorit.');
    }

    public function destroyFavorite(FavoritePlace $favorite)
    {
        abort_unless($favorite->user_id === auth()->id(), 403);
        $favorite->delete();
        return back()->with('success', 'Wisata dihapus dari favorit.');
    }

    public function trips()
    {
        $plans = TravelPlan::where('user_id', auth()->id())
            ->withCount(['places', 'places as visited_places_count' => fn ($q) => $q->where('visited', true)])
            ->with('places')->latest()->get();
        return view('trips.index', compact('plans'));
    }

    public function storeTrip(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:3000'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);
        $data['user_id'] = auth()->id();
        TravelPlan::create($data);
        return redirect()->route('trips.index')->with('success', 'Rencana perjalanan dibuat. Tambahkan tujuan wisata di bawah rencana tersebut.');
    }

    public function addTripPlace(Request $request, TravelPlan $trip)
    {
        abort_unless($trip->user_id === auth()->id(), 403);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'url' => ['nullable', 'url', 'max:2000'],
            'place_key' => ['nullable', 'string', 'max:64'],
        ]);
        $data['place_key'] = $data['place_key'] ?: hash('sha256', Str::lower(trim($data['name'].'|'.($data['address'] ?? ''))));
        $trip->places()->firstOrCreate(
            ['place_key' => $data['place_key']],
            ['name' => $data['name'], 'address' => $data['address'] ?? null, 'url' => $data['url'] ?? null]
        );
        return back()->with('success', 'Tujuan wisata ditambahkan ke rencana.');
    }

    public function toggleVisited(TravelPlan $trip, TravelPlanPlace $place)
    {
        abort_unless($trip->user_id === auth()->id() && $place->travel_plan_id === $trip->id, 403);
        if ($trip->completed_at) return back()->withErrors(['trip' => 'Rencana yang sudah selesai tidak dapat diubah.']);
        $place->visited = !$place->visited;
        $place->visited_at = $place->visited ? now() : null;
        $place->save();
        return back()->with('success', 'Progres kunjungan diperbarui.');
    }

    public function completeTrip(TravelPlan $trip)
    {
        abort_unless($trip->user_id === auth()->id(), 403);
        $total = $trip->places()->count();
        $visited = $trip->places()->where('visited', true)->count();
        if ($total === 0 || $total !== $visited) {
            return back()->withErrors(['trip' => 'Tandai semua tujuan sebagai sudah dikunjungi sebelum menyelesaikan perjalanan.']);
        }
        $trip->completed_at = now();
        $trip->save();
        return redirect()->route('achievements.index')->with('success', 'Selamat! Perjalanan tercatat sebagai pencapaian selesai.');
    }

    public function destroyTrip(TravelPlan $trip)
    {
        abort_unless($trip->user_id === auth()->id(), 403);
        $trip->delete();
        return back()->with('success', 'Rencana perjalanan dihapus.');
    }

    public function achievements()
    {
        $completedTrips = TravelPlan::where('user_id', auth()->id())
            ->whereNotNull('completed_at')->withCount('places')->with('places')->latest('completed_at')->get();
        $totalTrips = TravelPlan::where('user_id', auth()->id())->count();
        return view('trips.achievements', compact('completedTrips', 'totalTrips'));
    }
}
