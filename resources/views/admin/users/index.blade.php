@extends('admin.layout')

@section('title', 'Daftar Pengguna')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">Daftar Pengguna</h1>
        <p class="mt-1 text-gray-500">Lihat siapa saja yang login, favorit, rencana perjalanan, dan jumlah teman.</p>
    </div>

    <form method="GET" class="flex gap-2">
        <input type="hidden" name="status" value="{{ $status }}">
        <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama / email / username"
               class="w-64 rounded-lg border px-3 py-2 text-sm">
        <button class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Cari</button>
    </form>
</div>

{{-- Filter status login --}}
<div class="mb-4 flex flex-wrap gap-2 text-sm">
    @foreach (['all' => 'Semua', 'login' => 'Sedang login', 'offline' => 'Tidak login'] as $key => $label)
        <a href="{{ route('admin.users.index', ['status' => $key, 'q' => $search ?: null]) }}"
           class="rounded-full px-4 py-2 {{ $status === $key ? 'bg-red-600 font-semibold text-white' : 'border bg-white text-gray-700 hover:bg-gray-50' }}">
            {{ $label }} ({{ $totals[$key] }})
        </a>
    @endforeach
</div>

<div class="overflow-x-auto rounded-xl bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3">Pengguna</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Login Terakhir</th>
                <th class="px-4 py-3 text-center">Favorit</th>
                <th class="px-4 py-3 text-center">Rencana</th>
                <th class="px-4 py-3 text-center">Teman</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
        @forelse ($users as $u)
            @php
                $last   = $activity[$u->id] ?? null;
                $st     = $presence->status($last);
            @endphp
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-3">
                    <p class="font-semibold">
                        {{ $u->name }}
                        @if ($u->isAdmin())
                            <span class="ml-1 rounded bg-red-100 px-1.5 py-0.5 text-[10px] font-bold text-red-600">ADMIN</span>
                        @endif
                    </p>
                    <p class="text-xs text-gray-500">
                        @if ($u->username) &#64;{{ $u->username }} &middot; @endif{{ $u->email }}
                    </p>
                </td>
                <td class="px-4 py-3">
                    @include('admin.partials.status-badge', ['status' => $st])
                    @if ($last && $st !== 'offline')
                        <p class="mt-1 text-xs text-gray-500">aktif {{ \Carbon\Carbon::createFromTimestamp($last)->diffForHumans() }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-600">
                    {{ $u->last_login_at ? $u->last_login_at->format('d M Y H:i') : '—' }}
                </td>
                <td class="px-4 py-3 text-center">{{ $u->favorite_places_count }}</td>
                <td class="px-4 py-3 text-center">{{ $u->travel_plans_count }}</td>
                <td class="px-4 py-3 text-center">{{ $u->friends_total }}</td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.users.show', $u) }}"
                       class="rounded-lg border px-3 py-1.5 text-xs font-semibold hover:bg-gray-100">Detail</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="px-4 py-10 text-center text-gray-500">Tidak ada pengguna yang cocok.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $users->links() }}</div>

<p class="mt-4 text-xs text-gray-500">
    “Online” = aktif dalam {{ \App\Services\UserPresence::ONLINE_MINUTES }} menit terakhir.
    “Login (idle)” = sesi masih berlaku ({{ config('session.lifetime') }} menit) tetapi sedang tidak aktif.
</p>
@endsection