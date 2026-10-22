<?php

namespace App\Http\Controllers;

use App\Models\FavoritePlace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoritePlaceController extends Controller
{
    /**
     * Dipanggil dari peta: simpan atau hapus favorit (toggle).
     */
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
                'success' => true,
                'favorited' => false,
                'message' => 'Dihapus dari favorit.',
            ]);
        }

        $data['user_id'] = $userId;
        $data['user_rating_count'] = $data['user_rating_count'] ?? 0;

        FavoritePlace::create($data);

        return response()->json([
            'success' => true,
            'favorited' => true,
            'message' => 'Tersimpan di favorit.',
        ]);
    }

    /**
     * Dipanggil dari halaman profil: hapus favorit.
     */
    public function destroy(Request $request, FavoritePlace $favoritePlace): RedirectResponse
    {
        abort_unless($favoritePlace->user_id === $request->user()->id, 403);

        $favoritePlace->delete();

        return redirect()
            ->to(route('profile.show') . '#destinasi-favorit')
            ->with('status', 'Destinasi dihapus dari favorit.');
    }
}