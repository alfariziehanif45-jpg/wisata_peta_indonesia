<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FavoritePlaceController extends Controller
{
    /* =====================================================
       FAVORIT DARI TOMBOL HATI PADA POPUP PETA
    ====================================================== */

    /**
     * Daftar kunci tempat yang sudah difavoritkan pengguna.
     * Opsional: ?keys[]=a&keys[]=b untuk memeriksa kunci tertentu saja.
     */
    public function status(Request $request): JsonResponse
    {
        $query = $request->user()->favoritePlaces();

        $keys = $request->query('keys');

        if (is_array($keys) && count($keys) > 0) {
            $query->whereIn('place_key', array_map('strval', $keys));
        }

        return response()->json([
            'success'   => true,
            'favorites' => $query->pluck('place_key')->values(),
        ]);
    }

    /**
     * Simpan / hapus favorit (dipanggil dari tombol di peta).
     * Jika tempat sudah ada di favorit -> dihapus, jika belum -> disimpan.
     */
    public function toggle(Request $request): JsonResponse
    {
        $data = $request->validate([
            'place_key'         => ['required', 'string', 'max:191'],
            'name'              => ['required', 'string', 'max:255'],
            'category'          => ['required', 'in:wisata,kuliner'],
            'type'              => ['nullable', 'string', 'max:255'],
            'address'           => ['nullable', 'string', 'max:500'],
            'latitude'          => ['required', 'numeric', 'between:-90,90'],
            'longitude'         => ['required', 'numeric', 'between:-180,180'],
            'rating'            => ['nullable', 'numeric', 'between:0,5'],
            'user_rating_count' => ['nullable', 'integer', 'min:0'],
            // Hanya izinkan tautan http/https
            'google_maps_url'   => ['nullable', 'string', 'max:1000', 'regex:/^https?:\/\//i'],
            'province'          => ['nullable', 'string', 'max:100'],
            'city'              => ['nullable', 'string', 'max:100'],
        ]);

        $user = $request->user();

        $existing = $user->favoritePlaces()
            ->where('place_key', $data['place_key'])
            ->first();

        if ($existing) {
            $existing->delete();
            $favorited = false;
        } else {
            $user->favoritePlaces()->create($data);
            $favorited = true;
        }

        return response()->json([
            'success'   => true,
            'favorited' => $favorited,
            'total'     => $user->favoritePlaces()->count(),
        ]);
    }


    /* =====================================================
       FAVORIT MANUAL (tambah, lihat, edit, hapus)
    ====================================================== */

    /**
     * Daftar favorit tampil di profil (bagian "Destinasi Favorit Saya").
     */
    public function index(): RedirectResponse
    {
        return redirect()->to(route('profile.show') . '#destinasi-favorit');
    }

    public function create(): View
    {
        return view('favorites.form', ['favorite' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateManual($request);

        // Kunci unik untuk favorit yang diinput manual
        $data['place_key'] = 'manual:' . Str::uuid();

        $request->user()->favoritePlaces()->create($data);

        return redirect()
            ->to(route('profile.show') . '#destinasi-favorit')
            ->with('success', 'Destinasi favorit ditambahkan.');
    }

    public function edit(Request $request, $id): View
    {
        $favorite = $request->user()->favoritePlaces()->findOrFail($id);

        return view('favorites.form', ['favorite' => $favorite]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $favorite = $request->user()->favoritePlaces()->findOrFail($id);

        $favorite->update($this->validateManual($request));

        return redirect()
            ->to(route('profile.show') . '#destinasi-favorit')
            ->with('success', 'Destinasi favorit diperbarui.');
    }

    public function destroy(Request $request, $id): RedirectResponse
    {
        // Hanya bisa menghapus favorit milik sendiri
        $favorite = $request->user()->favoritePlaces()->findOrFail($id);

        $favorite->delete();

        return redirect()
            ->to(route('profile.show') . '#destinasi-favorit')
            ->with('success', 'Destinasi dihapus dari favorit.');
    }

    private function validateManual(Request $request): array
    {
        return $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'category'        => ['required', 'in:wisata,kuliner'],
            'type'            => ['nullable', 'string', 'max:255'],
            'address'         => ['nullable', 'string', 'max:500'],
            'city'            => ['nullable', 'string', 'max:100'],
            'province'        => ['nullable', 'string', 'max:100'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
            'google_maps_url' => ['nullable', 'string', 'max:1000', 'regex:/^https?:\/\//i'],
        ]);
    }
}