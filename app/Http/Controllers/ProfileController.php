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

        // Daftar destinasi favorit (terbaru di atas) untuk bagian "Destinasi Favorit"
        $favorites = FavoritePlace::where('user_id', $user->id)
            ->latest()
            ->get();

        $favoriteCount = $favorites->count();

        $tripCount = TravelPlan::where('user_id', $user->id)
            ->whereNull('completed_at')
            ->count();

        return view('profile.show', [
            'user' => $user,
            'favorites' => $favorites,
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

        $hex = ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

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

            'domicile' => ['nullable', 'string', 'max:100'],
            'bio'      => ['nullable', 'string', 'max:500'],

            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'password' => ['nullable', 'string', 'min:8', 'confirmed'],

            // ===== Sampul profil (foto/video bagian atas) =====
            'cover_type'   => ['required', Rule::in(['default', 'gradient', 'media'])],
            'cover_color'  => $hex,
            'cover_color2' => $hex,
            'cover_file'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,webm', 'max:20480'],

            // ===== Warna tulisan =====
            'name_color'     => $hex,
            'username_color' => $hex,

            // ===== Background halaman profil =====
            // 'salju' = latar musim dingin bawaan + efek salju jatuh
            'bg_type'   => ['required', Rule::in(['default', 'color', 'gradient', 'image', 'salju'])],
            'bg_color'  => $hex,
            'bg_color2' => $hex,
            'bg_file'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        // Data dasar
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->domicile = $validated['domicile'] ?? null;
        $user->bio = $validated['bio'] ?? null;

        // Foto profil
        if ($request->hasFile('avatar')) {
            $oldAvatar = $user->avatar_path;

            $user->avatar_path = $request
                ->file('avatar')
                ->store('profile-photos', 'public');

            if ($oldAvatar) {
                Storage::disk('public')->delete($oldAvatar);
            }
        }

        // Password
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Warna tulisan (kosong = kembali ke warna bawaan)
        $user->name_color = $validated['name_color'] ?? null;
        $user->username_color = $validated['username_color'] ?? null;

        $this->updateCover($request, $user, $validated);
        $this->updateBackground($request, $user, $validated);

        $user->save();

        return redirect()
            ->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Sampul profil: default, gradien, atau foto/video.
     */
    private function updateCover(Request $request, $user, array $validated): void
    {
        $choice = $validated['cover_type'];

        $user->cover_color  = $validated['cover_color']  ?? $user->cover_color;
        $user->cover_color2 = $validated['cover_color2'] ?? $user->cover_color2;

        if ($choice === 'media') {
            if ($request->hasFile('cover_file')) {
                $oldCover = $user->cover_path;
                $file = $request->file('cover_file');

                $user->cover_path = $file->store('covers', 'public');
                $user->cover_type = str_starts_with((string) $file->getMimeType(), 'video/')
                    ? 'video'
                    : 'image';

                if ($oldCover) {
                    Storage::disk('public')->delete($oldCover);
                }
            } elseif (!$user->cover_path) {
                $user->cover_type = 'default';
            }

            return;
        }

        if ($user->cover_path) {
            Storage::disk('public')->delete($user->cover_path);
            $user->cover_path = null;
        }

        $user->cover_type = $choice;
    }

    /**
     * Background halaman profil: default, warna, gradien, foto, atau salju.
     */
    private function updateBackground(Request $request, $user, array $validated): void
    {
        $choice = $validated['bg_type'];

        $user->bg_color  = $validated['bg_color']  ?? $user->bg_color;
        $user->bg_color2 = $validated['bg_color2'] ?? $user->bg_color2;

        if ($choice === 'image') {
            if ($request->hasFile('bg_file')) {
                $oldBg = $user->bg_path;

                $user->bg_path = $request->file('bg_file')->store('backgrounds', 'public');
                $user->bg_type = 'image';

                if ($oldBg) {
                    Storage::disk('public')->delete($oldBg);
                }
            } elseif (!$user->bg_path) {
                $user->bg_type = 'default';
            } else {
                $user->bg_type = 'image';
            }

            return;
        }

        // default / color / gradient / salju: tidak memakai file unggahan,
        // jadi hapus foto background lama (jika ada).
        if ($user->bg_path) {
            Storage::disk('public')->delete($user->bg_path);
            $user->bg_path = null;
        }

        $user->bg_type = $choice;
    }
}