<?php

namespace App\Http\Controllers;

use App\Models\FavoritePlace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FavoritePlaceController extends Controller
{
    public function index(Request $request): View
    {
        $favorites = FavoritePlace::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(9);

        return view('favorites.index', compact('favorites'));
    }

    public function create(): View
    {
        return view('favorites.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateFavorite($request);

        $data['user_id'] = $request->user()->id;
        $data['place_key'] = 'manual:' . $data['user_id'] . ':' . Str::uuid();
        $data['user_rating_count'] = $data['user_rating_count'] ?? 0;

        FavoritePlace::create($data);

        return redirect()
            ->route('favorites.index')
            ->with('success', 'Destinasi berhasil ditambahkan.');
    }

    public function edit(Request $request, FavoritePlace $favoritePlace): View
    {
        abort_unless((int) $favoritePlace->user_id === (int) $request->user()->id, 403);

        // Kirim dua nama variabel agar kompatibel dengan view yang memakai
        // $favoritePlace maupun $favorite.
        return view('favorites.edit', [
            'favoritePlace' => $favoritePlace,
            'favorite'      => $favoritePlace,
        ]);
    }

    public function update(Request $request, FavoritePlace $favoritePlace): RedirectResponse
    {
        abort_unless((int) $favoritePlace->user_id === (int) $request->user()->id, 403);

        $data = $this->validateFavorite($request);

        $favoritePlace->update($data);

        return redirect()
            ->route('favorites.index')
            ->with('success', 'Destinasi berhasil diperbarui.');
    }

    public function destroy(Request $request, FavoritePlace $favoritePlace): RedirectResponse
    {
        abort_unless((int) $favoritePlace->user_id === (int) $request->user()->id, 403);

        $favoritePlace->delete();

        return redirect()
            ->route('favorites.index')
            ->with('success', 'Destinasi dihapus dari favorit.');
    }

    public function toggle(Request $request): JsonResponse
    {
        $data = $request->validate([
            'place_key' => ['required', 'string', 'max:190'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:wisata,kuliner,hotel,ibadah'],
            'type' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'rating' => ['nullable', 'numeric', 'between:0,5'],
            'user_rating_count' => ['nullable', 'integer', 'min:0'],
            'google_maps_url' => ['nullable', 'string'],
            'province' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
        ]);

        $userId = $request->user()->id;

        $existing = FavoritePlace::where('user_id', $userId)
            ->where('place_key', $data['place_key'])
            ->first();

        if ($existing) {
            $existing->delete();

            return response()->json([
                'success'   => true,
                'favorited' => false,
                'message'   => 'Dihapus dari favorit.',
            ]);
        }

        $data['user_id'] = $userId;
        $data['user_rating_count'] = $data['user_rating_count'] ?? 0;
        $data['url'] = $data['google_maps_url'] ?? null;

        FavoritePlace::create($data);

        return response()->json([
            'success'   => true,
            'favorited' => true,
            'message'   => 'Tersimpan di favorit.',
        ]);
    }

    public function status(Request $request): JsonResponse
    {
        $data = $request->validate([
            'place_key' => ['required', 'string', 'max:190'],
        ]);

        $favorited = FavoritePlace::where('user_id', $request->user()->id)
            ->where('place_key', $data['place_key'])
            ->exists();

        return response()->json([
            'favorited' => $favorited,
        ]);
    }

    private function validateFavorite(Request $request): array
    {
        return $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'category'          => ['required', 'in:wisata,kuliner,hotel,ibadah'],
            'type'              => ['nullable', 'string', 'max:255'],
            'address'           => ['nullable', 'string'],
            'description'       => ['nullable', 'string'],
            'url'               => ['nullable', 'url', 'max:2048'],
            'latitude'          => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'         => ['nullable', 'numeric', 'between:-180,180'],
            'rating'            => ['nullable', 'numeric', 'between:0,5'],
            'user_rating_count' => ['nullable', 'integer', 'min:0'],
            'province'          => ['nullable', 'string', 'max:255'],
            'city'              => ['nullable', 'string', 'max:255'],
        ]);
    }
}