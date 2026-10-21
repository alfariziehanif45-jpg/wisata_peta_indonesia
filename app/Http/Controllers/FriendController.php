<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FriendController extends Controller
{
    public function index(Request $request)
    {
        $me = $request->user();
        $q  = ltrim(trim((string) $request->query('q', '')), '@');

        // ===== Pencarian username / nama =====
        $results   = collect();
        $relations = collect();

        if (mb_strlen($q) >= 2) {
            $like = '%' . addcslashes($q, '%_\\') . '%';

            $results = User::query()
                ->where('id', '!=', $me->id)
                ->where(fn ($w) => $w->where('username', 'like', $like)->orWhere('name', 'like', $like))
                ->orderByRaw('CASE WHEN username = ? THEN 0 WHEN name = ? THEN 1 ELSE 2 END', [$q, $q])
                ->orderBy('name')
                ->limit(20)
                ->get();

            $ids = $results->pluck('id');

            $relations = Friendship::query()
                ->where(fn ($w) => $w->where('requester_id', $me->id)->whereIn('addressee_id', $ids))
                ->orWhere(fn ($w) => $w->where('addressee_id', $me->id)->whereIn('requester_id', $ids))
                ->get()
                ->keyBy(fn ($f) => $f->requester_id === $me->id ? $f->addressee_id : $f->requester_id);
        }

        return view('friends.index', [
            'q'         => $q,
            'results'   => $results,
            'relations' => $relations,
            'friends'   => $me->acceptedFriendships()->with(['requester', 'addressee'])->latest()->get(),
            'incoming'  => $me->receivedRequests()->where('status', 'pending')->with('requester')->latest()->get(),
            'outgoing'  => $me->sentRequests()->where('status', 'pending')->with('addressee')->latest()->get(),
        ]);
    }

    /** Lihat profil teman (hanya baca, hanya untuk teman yang sudah diterima). */
    public function show(Request $request, User $user)
    {
        $me = $request->user();

        // Profil sendiri -> ke halaman profil yang bisa diedit.
        if ($user->id === $me->id) {
            return redirect()->route('profile.show');
        }

        $friendship = $this->acceptedFriendshipOrFail($me, $user);

        return view('friends.show', [
            'user'       => $user,
            'friendship' => $friendship,
            'profile'    => $this->profilePayload($user),
        ]);
    }

    /**
     * Data profil terbaru teman (JSON).
     * Dipanggil otomatis oleh halaman profil teman untuk sinkronisasi perubahan.
     * Jika sudah tidak berteman -> 403 (halaman akan kembali ke daftar teman).
     */
    public function data(Request $request, User $user): JsonResponse
    {
        $this->acceptedFriendshipOrFail($request->user(), $user);

        return response()
            ->json($this->profilePayload($user))
            ->header('Cache-Control', 'no-store, max-age=0');
    }

    /** Kirim permintaan pertemanan. */
    public function store(Request $request, User $user)
    {
        $me = $request->user();

        if ($user->id === $me->id) {
            return back()->withErrors('Kamu tidak bisa berteman dengan diri sendiri.');
        }

        $existing = $me->friendshipWith($user);

        if ($existing) {
            // Dia sudah lebih dulu mengirim permintaan -> langsung berteman.
            if ($existing->status === 'pending' && $existing->addressee_id === $me->id) {
                $existing->update(['status' => 'accepted']);
                return back()->with('success', 'Kamu sekarang berteman dengan ' . $user->name . '.');
            }

            return back()->withErrors($existing->isAccepted()
                ? 'Kalian sudah berteman.'
                : 'Permintaan pertemanan sudah dikirim.');
        }

        Friendship::create([
            'requester_id' => $me->id,
            'addressee_id' => $user->id,
            'status'       => 'pending',
        ]);

        return back()->with('success', 'Permintaan pertemanan dikirim ke ' . $user->name . '.');
    }

    /** Terima permintaan (hanya penerima). */
    public function accept(Request $request, Friendship $friendship)
    {
        abort_unless(
            $friendship->addressee_id === $request->user()->id && $friendship->status === 'pending',
            403
        );

        $friendship->update(['status' => 'accepted']);

        return back()->with('success', 'Permintaan pertemanan diterima.');
    }

    /** Tolak / batalkan permintaan / hapus teman (kedua pihak boleh). */
    public function destroy(Request $request, Friendship $friendship)
    {
        $me = $request->user();

        abort_unless(in_array($me->id, [$friendship->requester_id, $friendship->addressee_id], true), 403);

        $message = match (true) {
            $friendship->isAccepted()                => 'Teman dihapus.',
            $friendship->requester_id === $me->id    => 'Permintaan dibatalkan.',
            default                                  => 'Permintaan ditolak.',
        };

        $friendship->delete();

        return back()->with('success', $message);
    }

    /* ------------------------------------------------------------------
     |  Helper privat
     | ------------------------------------------------------------------ */

    /** Pastikan $me dan $user berteman (status accepted), jika tidak -> 403. */
    private function acceptedFriendshipOrFail(User $me, User $user): Friendship
    {
        $friendship = $me->friendshipWith($user);

        abort_unless(
            $friendship && $friendship->isAccepted(),
            403,
            'Kamu hanya bisa melihat profil teman yang sudah berteman denganmu.'
        );

        return $friendship;
    }

    /**
     * Semua data profil yang tampil di halaman teman.
     * Dipakai untuk render awal (Blade) DAN untuk polling (JSON),
     * jadi tampilannya selalu konsisten.
     *
     * 'version' = hash isi data; berubah hanya jika ada yang benar-benar berubah.
     */
    private function profilePayload(User $user): array
    {
        $v = $user->updated_at?->timestamp ?? 0; // hanya untuk cache-busting gambar

        $payload = [
            'name'           => $user->name,
            'username'       => $user->username ?: Str::slug($user->name, '_'),
            'domicile'       => $user->domicile,
            'bio'            => $user->bio,
            'tone'           => 't' . (((int) $user->id) % 6),
            'avatar'         => $user->avatar_path
                                    ? asset('storage/' . $user->avatar_path) . '?v=' . $v
                                    : null,

            // Sampul profil & background halaman -> nilai CSS siap pakai
            'cover'          => $this->paint($user->cover_type, $user->cover_color, $user->cover_color2, $user->cover_path, $v),
            'bg'             => $this->paint($user->bg_type, $user->bg_color, $user->bg_color2, $user->bg_path, $v),

            // Warna tulisan (null = pakai warna bawaan)
            'name_color'     => $this->color($user->name_color),
            'username_color' => $this->color($user->username_color),
        ];

        $payload['version'] = md5(json_encode($payload));

        return $payload;
    }

    /**
     * Ubah pengaturan tampilan jadi nilai CSS `background`.
     *
     * ASUMSI nilai tipe: 'image' | 'gradient' | 'color' (atau 'solid').
     * Jika di proyek Anda namanya berbeda (misal 'gambar', 'warna'),
     * ubah di bagian match() di bawah.
     */
    private function paint(?string $type, ?string $c1, ?string $c2, ?string $path, int $v): ?string
    {
        $c1 = $this->color($c1);
        $c2 = $this->color($c2);

        return match ($type) {
            'image'          => $path
                                    ? 'url("' . asset('storage/' . $path) . '?v=' . $v . '")'
                                    : null,
            'gradient'       => $c1 && $c2
                                    ? "linear-gradient(135deg, {$c1}, {$c2})"
                                    : $c1,
            'color', 'solid' => $c1,
            default          => null,
        };
    }

    /** Hanya izinkan warna hex / rgb() / hsl() agar aman dipakai di atribut style. */
    private function color(?string $value): ?string
    {
        $value = trim((string) $value);

        return preg_match('/^(#[0-9a-fA-F]{3,8}|(rgba?|hsla?)\([0-9\s.,%]+\))$/', $value)
            ? $value
            : null;
    }
}