{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layout')

@section('title', 'Dashboard')
@section('section', 'Ringkasan')

@section('content')
@php
    $hour  = now()->hour;
    $greet = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 18 ? 'Selamat sore' : 'Selamat malam'));
    $first = explode(' ', trim(auth()->user()->name ?? 'Admin'))[0];

    // Baris atas: kondisi pengguna saat ini
    $topCards = [
        ['Total pengguna',   $stats['users'],      'Terdaftar di situs',      'users'],
        ['Sedang login',     $stats['logged_in'],  'Punya sesi aktif',        'user-check'],
        ['Online',           $stats['online'],     'Aktif dalam 5 menit',     'wifi'],
        ['Pengguna baru',    $stats['new_users'],  'Dalam 7 hari terakhir',   'user-plus'],
    ];

    // Baris kedua: aktivitas wisata
    $donePct = $stats['trips'] > 0 ? round($stats['trips_done'] / $stats['trips'] * 100) : 0;
    $activityCards = [
        ['Favorit',            $stats['favorites'],   'Tempat yang disimpan pengguna',  'heart',        null],
        ['Rencana perjalanan', $stats['trips'],       'Dibuat oleh semua pengguna',      'route',        null],
        ['Perjalanan selesai', $stats['trips_done'],  'Dari seluruh rencana',            'check-circle', $donePct.'%'],
        ['Pertemanan aktif',   $stats['friendships'], 'Pasangan teman yang saling terhubung', 'users',   null],
    ];

    // Grafik: $dailyUsers = [['date' => '2026-10-01', 'total' => 3], ...] (opsional)
    $series = collect($dailyUsers ?? [])->values();
    $max    = max(1, (int) $series->max('total'));
    $ticks  = [$max, (int) round($max * 2 / 3), (int) round($max / 3), 0];
@endphp

{{-- ============ Judul ============ --}}
<div class="flex flex-wrap items-start justify-between gap-4">
    <div>
        <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-neutral-500">{{ $greet }}</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight">{{ $first }}, ini kondisi situs hari ini</h1>
        <p class="mt-1 text-[15px] text-neutral-600">Gambaran pengguna saat ini dan aktivitas wisata sejauh ini.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.places.index') }}"
           class="rounded-full bg-neutral-100 px-5 py-2.5 text-sm font-medium hover:bg-neutral-200">Statistik tempat</a>
        <a href="{{ route('admin.users.index') }}"
           class="rounded-full bg-neutral-950 px-5 py-2.5 text-sm font-medium text-white hover:bg-neutral-800">Kelola pengguna</a>
    </div>
</div>

{{-- ============ Kartu atas ============ --}}
<div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ($topCards as [$label, $value, $hint, $icon])
        <div class="rounded-2xl border border-neutral-200 bg-white p-5">
            <div class="flex items-center gap-3">
                <span class="grid size-9 place-items-center rounded-lg bg-neutral-100 text-neutral-700">
                    @include('admin.partials.icon', ['name' => $icon])
                </span>
                <span class="text-xs font-semibold uppercase tracking-[0.16em]">{{ $label }}</span>
            </div>
            <p class="mt-5 font-mono text-4xl font-bold tracking-tight">{{ number_format($value) }}</p>
            <p class="mt-2 text-sm text-neutral-500">{{ $hint }}</p>
        </div>
    @endforeach
</div>

{{-- ============ Aktivitas wisata ============ --}}
<section class="mt-5 rounded-2xl border border-neutral-200 bg-white/60 p-5">
    <h2 class="text-xs font-semibold uppercase tracking-[0.16em] text-neutral-600">Aktivitas wisata</h2>

    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($activityCards as [$label, $value, $hint, $icon, $badge])
            <div class="rounded-2xl border border-neutral-200 bg-white p-5">
                <div class="flex items-center gap-3">
                    <span class="grid size-9 place-items-center rounded-lg bg-neutral-100 text-neutral-700">
                        @include('admin.partials.icon', ['name' => $icon])
                    </span>
                    <span class="text-xs font-semibold uppercase tracking-[0.16em]">{{ $label }}</span>
                </div>
                <div class="mt-5 flex items-center gap-3">
                    <p class="font-mono text-4xl font-bold tracking-tight">{{ number_format($value) }}</p>
                    @if ($badge)
                        <span class="rounded-full bg-emerald-50 px-2 py-0.5 font-mono text-xs font-medium text-emerald-700">{{ $badge }}</span>
                    @endif
                </div>
                <p class="mt-2 text-sm text-neutral-500">{{ $hint }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ============ Grafik pengguna baru per hari ============ --}}
<section class="mt-5 rounded-2xl border border-neutral-200 bg-white p-5">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-xs font-semibold uppercase tracking-[0.16em] text-neutral-600">Pengguna baru per hari</h2>
        @if ($series->isNotEmpty())
            <span class="font-mono text-xs text-neutral-500">{{ number_format($series->sum('total')) }} pengguna dalam {{ $series->count() }} hari</span>
        @endif
    </div>

    @if ($series->isEmpty())
        <p class="mt-6 text-sm text-neutral-500">Belum ada data grafik. Kirim variabel <code class="font-mono">$dailyUsers</code> dari controller.</p>
    @else
        <div class="mt-5 overflow-x-auto">
            <div class="flex min-w-[720px] gap-3">
                {{-- Sumbu Y --}}
                <div class="flex h-56 w-10 flex-col justify-between text-right font-mono text-[11px] text-neutral-500">
                    @foreach ($ticks as $t)
                        <span class="-my-1.5">{{ $t }}</span>
                    @endforeach
                </div>

                <div class="flex-1">
                    <div class="relative h-56">
                        {{-- Garis bantu --}}
                        <div class="absolute inset-0 flex flex-col justify-between" aria-hidden="true">
                            @foreach ($ticks as $t)
                                <div class="border-t border-neutral-100"></div>
                            @endforeach
                        </div>

                        {{-- Batang --}}
                        <div class="absolute inset-0 flex items-end gap-1.5">
                            @foreach ($series as $row)
                                @php
                                    $v = (int) $row['total'];
                                    $isPeak = $v === $max && $max > 0;
                                    $date = \Illuminate\Support\Carbon::parse($row['date']);
                                @endphp
                                <div class="group flex h-full flex-1 items-end"
                                     title="{{ $date->translatedFormat('j F Y') }}: {{ $v }} pengguna baru">
                                    <div class="w-full rounded-full transition-colors group-hover:bg-neutral-800 {{ $isPeak ? 'bg-neutral-800' : 'bg-neutral-200' }}"
                                         style="height: {{ max(3, $v / $max * 100) }}%"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sumbu X --}}
                    <div class="mt-2 flex h-10 gap-1.5">
                        @foreach ($series as $row)
                            <div class="relative flex-1">
                                <span class="absolute right-0 top-0 origin-top-right -rotate-45 whitespace-nowrap font-mono text-[10px] text-neutral-500">
                                    {{ \Illuminate\Support\Carbon::parse($row['date'])->format('j/n') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>

{{-- ============ Daftar ============ --}}
<div class="mt-5 grid gap-5 lg:grid-cols-2">
    <section class="rounded-2xl border border-neutral-200 bg-white p-5">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-xs font-semibold uppercase tracking-[0.16em] text-neutral-600">Tempat paling difavoritkan</h2>
            <a href="{{ route('admin.places.index') }}" class="text-sm font-medium text-neutral-700 underline-offset-4 hover:underline">Lihat semua</a>
        </div>

        @forelse ($topFavorites as $i => $place)
            <div class="flex items-center justify-between gap-3 border-t border-neutral-100 py-3 first:border-t-0">
                <span class="flex min-w-0 items-center gap-3">
                    <span class="w-5 font-mono text-sm text-neutral-400">{{ $i + 1 }}</span>
                    <span class="truncate">{{ $place->name }}</span>
                </span>
                <span class="shrink-0 rounded-full bg-neutral-100 px-2.5 py-1 font-mono text-xs font-medium">
                    {{ number_format($place->total) }} user
                </span>
            </div>
        @empty
            <p class="py-2 text-sm text-neutral-500">Belum ada tempat favorit. Data muncul setelah pengguna menyimpan tempat.</p>
        @endforelse
    </section>

    <section class="rounded-2xl border border-neutral-200 bg-white p-5">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-xs font-semibold uppercase tracking-[0.16em] text-neutral-600">Pengguna terbaru</h2>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-neutral-700 underline-offset-4 hover:underline">Lihat semua</a>
        </div>

        @forelse ($latestUsers as $u)
            <a href="{{ route('admin.users.show', $u) }}"
               class="-mx-2 flex items-center justify-between gap-3 rounded-xl border-t border-neutral-100 px-2 py-3 first:border-t-0 hover:bg-neutral-50">
                <span class="flex min-w-0 items-center gap-3">
                    <span class="grid size-9 shrink-0 place-items-center rounded-full bg-neutral-100 text-xs font-semibold">
                        {{ mb_strtoupper(mb_substr($u->name, 0, 1)) }}
                    </span>
                    <span class="min-w-0 leading-tight">
                        <span class="block truncate">{{ $u->name }}</span>
                        <span class="block truncate text-xs text-neutral-500">{{ $u->email }}</span>
                    </span>
                </span>
                <span class="shrink-0 text-xs text-neutral-500">{{ $u->created_at?->diffForHumans() }}</span>
            </a>
        @empty
            <p class="py-2 text-sm text-neutral-500">Belum ada pengguna terdaftar.</p>
        @endforelse
    </section>
</div>
@endsection