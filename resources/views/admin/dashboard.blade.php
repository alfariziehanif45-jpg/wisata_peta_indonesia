@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<h1 class="mb-6 text-3xl font-bold">Dashboard</h1>

@php
    $cards = [
        ['Total Pengguna',     $stats['users'],       'text-gray-900'],
        ['Sedang Login',       $stats['logged_in'],   'text-green-600'],
        ['Online (5 menit)',   $stats['online'],      'text-green-600'],
        ['User Baru (7 hari)', $stats['new_users'],   'text-blue-600'],
        ['Total Favorit',      $stats['favorites'],   'text-red-600'],
        ['Rencana Perjalanan', $stats['trips'],       'text-gray-900'],
        ['Perjalanan Selesai', $stats['trips_done'],  'text-gray-900'],
        ['Pertemanan Aktif',   $stats['friendships'], 'text-gray-900'],
    ];
@endphp

<div class="grid grid-cols-2 gap-4 md:grid-cols-4">
    @foreach ($cards as [$label, $value, $color])
        <div class="rounded-xl bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">{{ $label }}</p>
            <p class="mt-1 text-3xl font-bold {{ $color }}">{{ number_format($value) }}</p>
        </div>
    @endforeach
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-2">
    <section class="rounded-xl bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Tempat Paling Difavoritkan</h2>
            <a href="{{ route('admin.places.index') }}" class="text-sm text-red-600 hover:underline">Lihat semua</a>
        </div>

        @forelse ($topFavorites as $i => $place)
            <div class="flex items-center justify-between border-t py-3 first:border-t-0">
                <span><span class="mr-2 text-gray-400">{{ $i + 1 }}.</span>{{ $place->name }}</span>
                <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-600">
                    {{ $place->total }} user
                </span>
            </div>
        @empty
            <p class="text-sm text-gray-500">Belum ada data favorit.</p>
        @endforelse
    </section>

    <section class="rounded-xl bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Pengguna Terbaru</h2>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-red-600 hover:underline">Lihat semua</a>
        </div>

        @forelse ($latestUsers as $u)
            <a href="{{ route('admin.users.show', $u) }}"
               class="flex items-center justify-between border-t py-3 first:border-t-0 hover:bg-gray-50">
                <span>
                    {{ $u->name }}
                    <span class="block text-xs text-gray-500">{{ $u->email }}</span>
                </span>
                <span class="text-xs text-gray-500">{{ $u->created_at?->diffForHumans() }}</span>
            </a>
        @empty
            <p class="text-sm text-gray-500">Belum ada pengguna.</p>
        @endforelse
    </section>
</div>
@endsection