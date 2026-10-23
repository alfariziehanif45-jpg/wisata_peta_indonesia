@extends('admin.layout')

@section('title', 'Detail ' . $user->name)

@section('content')
<a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-800">&larr; Kembali ke daftar</a>

<div class="mt-3 mb-6 flex flex-wrap items-start justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">
            {{ $user->name }}
            @if ($user->isAdmin())
                <span class="ml-1 rounded bg-red-100 px-2 py-0.5 text-xs font-bold text-red-600 align-middle">ADMIN</span>
            @endif
        </h1>
        <p class="mt-1 text-gray-500">
            @if ($user->username) &#64;{{ $user->username }} &middot; @endif{{ $user->email }}
            @if ($user->domicile) &middot; {{ $user->domicile }} @endif
        </p>
        @if ($user->bio)
            <p class="mt-2 max-w-2xl text-sm text-gray-600">{{ $user->bio }}</p>
        @endif
    </div>
    @include('admin.partials.status-badge', ['status' => $status])
</div>

<div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
    @php
        $cards = [
            ['Favorit',            $user->favorite_places_count],
            ['Rencana Perjalanan', $user->travel_plans_count],
            ['Teman',              $user->friends_total],
            ['Bergabung',          $user->created_at?->format('d M Y')],
            ['Login Terakhir',     $user->last_login_at?->format('d M Y H:i') ?? '—'],
            ['Aktivitas Terakhir', $lastActivity ? \Carbon\Carbon::createFromTimestamp($lastActivity)->diffForHumans() : '—'],
        ];
    @endphp
    @foreach ($cards as [$label, $value])
        <div class="rounded-xl bg-white p-4 shadow-sm">
            <p class="text-xs text-gray-500">{{ $label }}</p>
            <p class="mt-1 text-lg font-bold">{{ $value }}</p>
        </div>
    @endforeach
</div>

{{-- FAVORIT --}}
<section class="mt-8 rounded-xl bg-white p-5 shadow-sm">
    <h2 class="mb-4 text-lg font-semibold">Tempat Favorit ({{ $favorites->count() }})</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="text-xs uppercase text-gray-500">
                <tr>
                    <th class="py-2 pr-4">Nama</th>
                    <th class="py-2 pr-4">Kategori</th>
                    <th class="py-2 pr-4">Lokasi</th>
                    <th class="py-2 pr-4">Rating</th>
                    <th class="py-2">Disimpan</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($favorites as $f)
                <tr class="border-t">
                    <td class="py-2 pr-4 font-medium">{{ $f->name }}</td>
                    <td class="py-2 pr-4">{{ $f->category ?? '—' }}</td>
                    <td class="py-2 pr-4 text-gray-600">{{ collect([$f->city, $f->province])->filter()->implode(', ') ?: '—' }}</td>
                    <td class="py-2 pr-4">{{ $f->rating ?? '—' }}</td>
                    <td class="py-2 text-gray-500">{{ $f->created_at?->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-6 text-center text-gray-500">Belum ada favorit.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- RENCANA PERJALANAN --}}
<section class="mt-6 rounded-xl bg-white p-5 shadow-sm">
    <h2 class="mb-4 text-lg font-semibold">Rencana Perjalanan ({{ $plans->count() }})</h2>

    <div class="grid gap-4 md:grid-cols-2">
        @forelse ($plans as $plan)
            <div class="rounded-lg border p-4">
                <div class="flex items-start justify-between gap-2">
                    <p class="font-semibold">{{ $plan->title }}</p>
                    @if ($plan->completed_at)
                        <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700">Selesai</span>
                    @else
                        <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-700">Berjalan</span>
                    @endif
                </div>

                <p class="mt-1 text-xs text-gray-500">
                    @if ($plan->start_date)
                        {{ $plan->start_date->format('d M Y') }}
                        @if ($plan->end_date) – {{ $plan->end_date->format('d M Y') }} @endif
                    @else
                        Tanggal belum ditentukan
                    @endif
                </p>

                <div class="mt-3 h-2 overflow-hidden rounded-full bg-gray-200">
                    <div class="h-full bg-red-600" style="width: {{ $plan->progress }}%"></div>
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    {{ $plan->visited_places_count }} dari {{ $plan->places_count }} tempat dikunjungi ({{ $plan->progress }}%)
                </p>
            </div>
        @empty
            <p class="text-sm text-gray-500">Belum ada rencana perjalanan.</p>
        @endforelse
    </div>
</section>

{{-- TEMAN --}}
<section class="mt-6 rounded-xl bg-white p-5 shadow-sm">
    <h2 class="mb-4 text-lg font-semibold">Teman ({{ $friends->count() }})</h2>

    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($friends as $friend)
            <a href="{{ route('admin.users.show', $friend) }}"
               class="rounded-lg border px-4 py-3 hover:bg-gray-50">
                <p class="font-medium">{{ $friend->name }}</p>
                <p class="text-xs text-gray-500">{{ $friend->username ? '@'.$friend->username : $friend->email }}</p>
            </a>
        @empty
            <p class="text-sm text-gray-500">Belum punya teman.</p>
        @endforelse
    </div>
</section>
@endsection