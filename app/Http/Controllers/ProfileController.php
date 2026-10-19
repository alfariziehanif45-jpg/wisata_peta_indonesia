<?php

namespace App\Http\Controllers;

use App\Models\FavoritePlace;
use App\Models\TravelPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna.
     */
    public function show()
    {
        $user = auth()->user();

        $favoriteCount = FavoritePlace::where('user_id', $user->id)
            ->count();

        $tripCount = TravelPlan::where('user_id', $user->id)
            ->whereNull('completed_at')
            ->count();

        return view('profile.show', [
            'user' => $user,
            'favoriteCount' => $favoriteCount,
            'tripCount' => $tripCount,
        ]);
    }

    /**
     * Menampilkan formulir edit profil.
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Memproses perubahan data profil.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Za-z0-9_]+$/',
                Rule::unique('users', 'username')->ignore($user->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'domicile' => [
                'nullable',
                'string',
                'max:100',
            ],

            'bio' => [
                'nullable',
                'string',
                'max:500',
            ],

            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        // Memperbarui data dasar pengguna.
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->domicile = $validated['domicile'] ?? null;
        $user->bio = $validated['bio'] ?? null;

        // Memperbarui foto profil jika pengguna mengunggah foto baru.
        if ($request->hasFile('avatar')) {
            $oldAvatar = $user->avatar_path;

            $user->avatar_path = $request
                ->file('avatar')
                ->store('profile-photos', 'public');

            // Menghapus foto profil lama setelah foto baru disimpan.
            if ($oldAvatar) {
                Storage::disk('public')->delete($oldAvatar);
            }
        }

        // Mengubah password hanya jika kolom password diisi.
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui!');
    }
}
