<?php

namespace App\Http\Controllers;

use App\Models\FavoritePlace;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FavoritePlaceController extends Controller
{
    private function userFavorites()
    {
        return FavoritePlace::where('user_id', auth()->id());
    }

    // Menampilkan daftar favorit
    public function index()
    {
        $favorites = $this->userFavorites()
            ->latest()
            ->paginate(9);

        return view('favorites.index', compact('favorites'));
    }

    // Form tambah destinasi
    public function create()
    {
        return view('favorites.create');
    }

    // Menyimpan destinasi baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => [
                'required',
                Rule::in([
                    'Wisata Alam',
                    'Kuliner',
                    'Sejarah dan Budaya',
                    'Penginapan',
                    'Hiburan',
                    'Lainnya',
                ]),
            ],
            'address' => ['nullable', 'string', 'max:1000'],
            'url' => ['nullable', 'url', 'max:2048'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $data['user_id'] = $request->user()->id;

        // place_key dibuat unik untuk destinasi manual
        $data['place_key'] = 'manual_' . uniqid();

        FavoritePlace::create($data);

        return redirect()
            ->route('favorites.index')
            ->with('success', 'Destinasi berhasil ditambahkan ke favorit.');
    }

    // Form edit destinasi milik pengguna
    public function edit(string $id)
    {
        $favorite = $this->userFavorites()->findOrFail($id);

        return view('favorites.edit', compact('favorite'));
    }

    // Memperbarui destinasi
    public function update(Request $request, string $id)
    {
        $favorite = $this->userFavorites()->findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => [
                'required',
                Rule::in([
                    'Wisata Alam',
                    'Kuliner',
                    'Sejarah dan Budaya',
                    'Penginapan',
                    'Hiburan',
                    'Lainnya',
                ]),
            ],
            'address' => ['nullable', 'string', 'max:1000'],
            'url' => ['nullable', 'url', 'max:2048'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $favorite->update($data);

        return redirect()
            ->route('favorites.index')
            ->with('success', 'Destinasi favorit berhasil diperbarui.');
    }

    // Menghapus destinasi milik pengguna
    public function destroy(string $id)
    {
        $favorite = $this->userFavorites()->findOrFail($id);

        $favorite->delete();

        return redirect()
            ->route('favorites.index')
            ->with('success', 'Destinasi berhasil dihapus dari favorit.');
    }

    // Memeriksa status favorit untuk popup peta
    public function status(Request $request)
    {
        $request->validate([
            'place_key' => ['required', 'string', 'max:64'],
        ]);

        $saved = $this->userFavorites()
            ->where('place_key', $request->place_key)
            ->exists();

        return response()->json([
            'success' => true,
            'saved' => $saved,
        ]);
    }

    // Toggle favorit dari popup peta yang lama
    public function toggle(Request $request)
    {
        $data = $request->validate([
            'place_key' => ['required', 'string', 'max:64'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'url' => ['nullable', 'url', 'max:2048'],
        ]);

        $favorite = $this->userFavorites()
            ->where('place_key', $data['place_key'])
            ->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'success' => true,
                'saved' => false,
                'message' => 'Favorit berhasil dihapus.',
            ]);
        }

        $data['user_id'] = $request->user()->id;
        $data['category'] = 'Kuliner';

        FavoritePlace::create($data);

        return response()->json([
            'success' => true,
            'saved' => true,
            'message' => 'Favorit berhasil disimpan.',
        ]);
    }
}
