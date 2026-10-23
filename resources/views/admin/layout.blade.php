<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - Jelajah Wisata Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 text-gray-800">

@php
    $menu = [
        ['admin.dashboard',   'Dashboard',          'admin.dashboard'],
        ['admin.users.index', 'Pengguna',           'admin.users.*'],
        ['admin.places.index','Statistik Tempat',   'admin.places.*'],
    ];
@endphp

<header class="bg-gray-950 text-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-3">
        <div class="flex items-center gap-2 text-lg font-bold">
            <span class="rounded bg-red-600 px-2 py-0.5 text-sm">ADMIN</span>
            <span>Jelajah Wisata Indonesia</span>
        </div>

        <nav class="flex flex-wrap items-center gap-1 text-sm">
            @foreach ($menu as [$route, $label, $pattern])
                <a href="{{ route($route) }}"
                   class="rounded-lg px-3 py-2 transition {{ request()->routeIs($pattern) ? 'bg-red-600 font-semibold' : 'text-gray-300 hover:bg-white/10' }}">
                    {{ $label }}
                </a>
            @endforeach

            <a href="{{ route('map.index') }}"
               class="rounded-lg px-3 py-2 text-gray-300 hover:bg-white/10">Lihat Situs</a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="rounded-lg px-3 py-2 text-gray-300 hover:bg-white/10">Keluar</button>
            </form>
        </nav>
    </div>
</header>

<main class="mx-auto max-w-7xl px-4 py-8">
    @yield('content')
</main>

</body>
</html>