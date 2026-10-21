<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;

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
}