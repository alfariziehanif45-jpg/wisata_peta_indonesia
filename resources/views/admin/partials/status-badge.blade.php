@php
    $map = [
        'online'  => ['Online',       'bg-green-100 text-green-700', 'bg-green-500'],
        'idle'    => ['Login (idle)', 'bg-amber-100 text-amber-700', 'bg-amber-500'],
        'offline' => ['Tidak login',  'bg-gray-200 text-gray-600',   'bg-gray-400'],
    ];
    [$label, $cls, $dot] = $map[$status] ?? $map['offline'];
@endphp

<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $cls }}">
    <span class="h-2 w-2 rounded-full {{ $dot }}"></span>{{ $label }}
</span>