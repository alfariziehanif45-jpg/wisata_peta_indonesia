{{-- resources/views/admin/layout.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - Jelajah Wisata Indonesia</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'ui-monospace', 'monospace'],
                    },
                },
            },
        };
    </script>
    <style>
        .sidebar-scroll::-webkit-scrollbar { width: 6px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #d4d4d4; border-radius: 9999px; }
    </style>
</head>

@php
    // [route, label, pola route aktif, ikon]
    $groups = [
        'Ringkasan' => [
            ['admin.dashboard',    'Dashboard',        'admin.dashboard',  'dashboard'],
        ],
        'Kelola' => [
            ['admin.users.index',  'Pengguna',         'admin.users.*',    'users'],
            ['admin.places.index', 'Statistik Tempat', 'admin.places.*',   'map-pin'],
        ],
        'Situs' => [
            ['map.index',          'Lihat Situs',      null,               'globe'],
        ],
    ];

    $authUser = auth()->user();
    $userName = $authUser->name ?? 'Admin';
    $initials = collect(preg_split('/\s+/', trim($userName)))
        ->take(2)
        ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))
        ->join('');
@endphp

<body class="min-h-screen bg-[#f4f4f4] font-sans text-neutral-900 antialiased">

{{-- ============ TOPBAR ============ --}}
<header class="sticky top-0 z-40 flex h-16 items-center gap-3 bg-[#f4f4f4] px-4 lg:px-6">
    <div class="flex items-center gap-3 lg:w-[236px]">
        <button id="sidebar-toggle" type="button"
                class="rounded-lg p-2 text-neutral-600 hover:bg-white lg:hidden"
                aria-label="Buka menu" aria-controls="sidebar" aria-expanded="false">
            @include('admin.partials.icon', ['name' => 'panel'])
        </button>

        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-[17px] tracking-tight">
            @include('admin.partials.icon', ['name' => 'pulse', 'class' => 'size-6'])
            <span><span class="font-semibold">Jelajah</span> <span class="font-normal text-neutral-600">Wisata</span></span>
        </a>
    </div>

    <div class="hidden items-center gap-3 text-sm lg:flex">
        <span class="text-neutral-400">@include('admin.partials.icon', ['name' => 'panel'])</span>
        <span class="h-5 w-px bg-neutral-300"></span>
        <nav class="flex items-center gap-2" aria-label="Breadcrumb">
            <span class="text-[11px] font-medium uppercase tracking-[0.16em] text-neutral-500">@yield('section', 'Ringkasan')</span>
            @include('admin.partials.icon', ['name' => 'chevron-right', 'class' => 'size-3.5 text-neutral-400'])
            <span class="font-medium">@yield('title', 'Admin')</span>
        </nav>
    </div>

    <div class="ml-auto flex items-center gap-3">
        <label class="relative hidden sm:block">
            <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-700">
                @include('admin.partials.icon', ['name' => 'search', 'class' => 'size-4'])
            </span>
            <input id="goto" type="text" autocomplete="off" placeholder="Pergi ke..."
                   class="h-10 w-72 rounded-full border border-neutral-200 bg-white/70 pl-10 pr-14 text-sm placeholder:text-neutral-500 focus:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-200">
            <kbd class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 rounded-md bg-neutral-100 px-1.5 py-0.5 font-mono text-[10px] text-neutral-500">Ctrl K</kbd>
        </label>

        <button type="button" class="relative rounded-full p-2 text-neutral-700 hover:bg-white" aria-label="Notifikasi">
            @include('admin.partials.icon', ['name' => 'bell', 'class' => 'size-5'])
        </button>
    </div>
</header>

<div class="flex">
    {{-- ============ SIDEBAR ============ --}}
    <aside id="sidebar"
           class="sidebar-scroll fixed bottom-0 left-0 top-16 z-30 flex w-[260px] -translate-x-full flex-col overflow-y-auto bg-[#f4f4f4] transition-transform lg:sticky lg:h-[calc(100vh-4rem)] lg:translate-x-0">

        <nav class="flex-1 space-y-6 px-4 pb-4 pt-2" aria-label="Menu utama">
            @foreach ($groups as $groupLabel => $items)
                <div>
                    <p class="mb-2 px-3 text-[11px] font-medium uppercase tracking-[0.16em] text-neutral-500">{{ $groupLabel }}</p>
                    <div class="space-y-0.5">
                        @foreach ($items as [$route, $label, $pattern, $icon])
                            @php $active = $pattern && request()->routeIs($pattern); @endphp
                            <a href="{{ route($route) }}" data-nav
                               @if ($active) aria-current="page" @endif
                               class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-[15px] transition
                                      {{ $active ? 'bg-white font-medium shadow-sm' : 'text-neutral-700 hover:bg-white/70' }}">
                                @include('admin.partials.icon', ['name' => $icon])
                                <span>{{ $label }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>

        {{-- Profil + keluar --}}
        <div class="sticky bottom-0 border-t border-neutral-200 bg-[#f4f4f4] p-4">
            <div class="flex items-center gap-3">
                <span class="grid size-9 place-items-center rounded-full bg-white text-xs font-semibold shadow-sm">{{ $initials }}</span>
                <div class="min-w-0 flex-1 leading-tight">
                    <p class="truncate text-sm font-medium">{{ $userName }}</p>
                    <p class="text-xs text-neutral-500">Administrator</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar" aria-label="Keluar"
                            class="rounded-lg p-2 text-neutral-500 hover:bg-white hover:text-neutral-900">
                        @include('admin.partials.icon', ['name' => 'logout'])
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Latar gelap untuk sidebar mobile --}}
    <div id="sidebar-backdrop" class="fixed inset-0 top-16 z-20 hidden bg-black/20 lg:hidden"></div>

    {{-- ============ KONTEN ============ --}}
    <main class="min-w-0 flex-1 pb-4 pl-0 pr-4 lg:pl-0">
        <div class="ml-4 min-h-[calc(100vh-5rem)] rounded-3xl border border-neutral-200 bg-[#fafafa] p-5 sm:p-6 lg:ml-0">
            @yield('content')
        </div>
    </main>
</div>

<script>
    (function () {
        // Sidebar mobile
        const sidebar  = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const toggle   = document.getElementById('sidebar-toggle');

        function setSidebar(open) {
            sidebar.classList.toggle('-translate-x-full', !open);
            backdrop.classList.toggle('hidden', !open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }
        toggle.addEventListener('click', () => setSidebar(sidebar.classList.contains('-translate-x-full')));
        backdrop.addEventListener('click', () => setSidebar(false));

        // "Pergi ke..." : ketik nama menu lalu Enter. Ctrl/Cmd+K untuk fokus.
        const input = document.getElementById('goto');
        const links = [...document.querySelectorAll('[data-nav]')];

        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                input.focus();
                input.select();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key !== 'Enter') return;
            const q = input.value.trim().toLowerCase();
            if (!q) return;
            const hit = links.find((a) => a.textContent.trim().toLowerCase().includes(q));
            if (hit) window.location.href = hit.href;
        });
    })();
</script>
</body>
</html>