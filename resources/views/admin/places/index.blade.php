@extends('admin.layout')

@section('title', 'Statistik Tempat')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold">Statistik Tempat</h1>
    <p class="mt-1 text-gray-500">Tempat mana yang paling diminati pengguna, dan seberapa banyak rencana yang benar-benar dikunjungi.</p>
</div>

@if ($summary['total_favorites'] === 0 && $summary['total_planned'] === 0)

    <div class="rounded-xl border-2 border-dashed border-gray-300 bg-white p-12 text-center">
        <p class="text-4xl">📊</p>
        <p class="mt-3 text-lg font-semibold">Belum ada data untuk ditampilkan</p>
        <p class="mt-1 text-sm text-gray-500">
            Grafik akan muncul setelah pengguna menyimpan tempat favorit atau menandai tempat sebagai “sudah dikunjungi”.
        </p>
    </div>

@else

@php
    $rankStyles = ['bg-amber-400 text-white', 'bg-gray-300 text-gray-700', 'bg-orange-300 text-white'];
    $change = $trend['change'];
@endphp

{{-- ================= 1. RINGKASAN SINGKAT ================= --}}
<section class="rounded-2xl bg-gradient-to-r from-gray-900 to-gray-800 p-6 text-white shadow-sm">
    <h2 class="text-lg font-semibold">📌 Ringkasan singkat</h2>
    <p class="mb-4 text-sm text-gray-300">Intisari data saat ini dalam satu pandang.</p>

    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($insights as $item)
            <div class="rounded-xl bg-white/10 p-4">
                <p class="text-xs text-gray-300">{{ $item['icon'] }} {{ $item['title'] }}</p>
                <p class="mt-1 text-sm font-semibold leading-snug">{{ $item['text'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ================= 2. ANGKA UTAMA ================= --}}
<div class="mt-6 grid grid-cols-2 gap-4 md:grid-cols-4">
    <div class="rounded-xl bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">Tempat unik difavoritkan</p>
        <p class="mt-1 text-3xl font-bold text-red-600">{{ number_format($summary['unique_favorites']) }}</p>
        <p class="mt-1 text-xs text-gray-400">Jumlah tempat berbeda yang pernah disimpan</p>
    </div>

    <div class="rounded-xl bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">Total favorit tersimpan</p>
        <p class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($summary['total_favorites']) }}</p>
        <p class="mt-1 text-xs text-gray-400">Semua penyimpanan oleh semua pengguna</p>
    </div>

    <div class="rounded-xl bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">Tempat direncanakan</p>
        <p class="mt-1 text-3xl font-bold text-blue-600">{{ number_format($summary['total_planned']) }}</p>
        <p class="mt-1 text-xs text-gray-400">Masuk ke rencana perjalanan</p>
    </div>

    <div class="rounded-xl bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">Sudah dikunjungi</p>
        <p class="mt-1 text-3xl font-bold text-green-600">{{ number_format($summary['total_visited']) }}</p>
        <p class="mt-1 text-xs text-gray-400">{{ $summary['conversion'] }}% dari yang direncanakan</p>
    </div>
</div>

{{-- ================= 3. RENCANA -> KUNJUNGAN ================= --}}
<section class="mt-6 rounded-xl bg-white p-6 shadow-sm">
    <h2 class="text-lg font-semibold">🧳 Dari rencana ke kunjungan</h2>
    <p class="mb-5 text-xs text-gray-500">💡 Menunjukkan berapa banyak tempat dalam rencana perjalanan yang benar-benar sudah didatangi.</p>

    @if ($summary['total_planned'] > 0)
        <div class="space-y-4">
            <div>
                <div class="mb-1 flex justify-between text-sm">
                    <span class="font-medium">Direncanakan</span>
                    <span class="font-bold text-blue-600">{{ number_format($summary['total_planned']) }} tempat · 100%</span>
                </div>
                <div class="h-8 rounded-lg bg-blue-400"></div>
            </div>

            <div>
                <div class="mb-1 flex justify-between text-sm">
                    <span class="font-medium">Sudah dikunjungi</span>
                    <span class="font-bold text-green-600">{{ number_format($summary['total_visited']) }} tempat · {{ $summary['conversion'] }}%</span>
                </div>
                <div class="h-8 overflow-hidden rounded-lg bg-gray-100">
                    <div class="h-full rounded-lg bg-green-500" style="width: {{ $summary['conversion'] }}%"></div>
                </div>
            </div>
        </div>

        <p class="mt-4 rounded-lg bg-gray-50 p-3 text-sm text-gray-700">
            Dari <b>{{ number_format($summary['total_planned']) }}</b> tempat yang direncanakan,
            <b class="text-green-600">{{ number_format($summary['total_visited']) }}</b> sudah dikunjungi
            dan <b class="text-blue-600">{{ number_format($summary['not_visited']) }}</b> belum.
            @if ($summary['conversion'] >= 60)
                Pengguna cukup konsisten merealisasikan rencananya.
            @elseif ($summary['conversion'] >= 30)
                Sebagian rencana sudah terlaksana, sisanya masih menunggu.
            @else
                Masih banyak rencana yang belum terlaksana.
            @endif
        </p>
    @else
        <p class="py-8 text-center text-sm text-gray-500">Belum ada tempat dalam rencana perjalanan.</p>
    @endif
</section>

{{-- ================= 4. PERINGKAT ================= --}}
<div class="mt-6 grid gap-6 lg:grid-cols-2">

    {{-- Peringkat favorit --}}
    <section class="rounded-xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">❤️ 10 Tempat Paling Difavoritkan</h2>
        <p class="mb-5 text-xs text-gray-500">💡 Semakin panjang batang merah, semakin banyak pengguna yang menyimpan tempat itu.</p>

        <ol class="space-y-4">
            @forelse ($topFavorites as $i => $p)
                <li class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $rankStyles[$i] ?? 'bg-gray-100 text-gray-500' }}">
                        {{ $i + 1 }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-baseline justify-between gap-2">
                            <p class="truncate font-medium" title="{{ $p['name'] }}">{{ $p['name'] }}</p>
                            <p class="shrink-0 text-sm font-bold text-red-600">
                                {{ $p['total'] }} <span class="font-normal text-gray-400">pengguna</span>
                            </p>
                        </div>
                        <p class="truncate text-xs text-gray-500">
                            {{ $p['category'] }}@if ($p['location']) · {{ $p['location'] }}@endif
                            · {{ $p['share'] }}% dari semua favorit
                        </p>
                        <div class="mt-1.5 h-2.5 overflow-hidden rounded-full bg-gray-100">
                            <div class="h-full rounded-full bg-red-500" style="width: {{ $p['bar'] }}%"></div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="py-8 text-center text-sm text-gray-500">Belum ada tempat favorit.</li>
            @endforelse
        </ol>
    </section>

    {{-- Peringkat dikunjungi --}}
    <section class="rounded-xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">✅ 10 Tempat Paling Sering Dikunjungi</h2>
        <p class="mb-2 text-xs text-gray-500">💡 Batang hijau = sudah dikunjungi, batang biru muda = total yang direncanakan.</p>

        <div class="mb-4 flex flex-wrap gap-4 text-xs text-gray-600">
            <span class="inline-flex items-center gap-1.5"><span class="h-3 w-3 rounded bg-green-500"></span>Sudah dikunjungi</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-3 w-3 rounded bg-blue-200"></span>Direncanakan</span>
        </div>

        <ol class="space-y-4">
            @forelse ($topVisited as $i => $p)
                <li class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $rankStyles[$i] ?? 'bg-gray-100 text-gray-500' }}">
                        {{ $i + 1 }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-baseline justify-between gap-2">
                            <p class="truncate font-medium" title="{{ $p['name'] }}">{{ $p['name'] }}</p>
                            <p class="shrink-0 text-sm font-bold text-green-600">
                                {{ $p['visited'] }}<span class="font-normal text-gray-400"> dari {{ $p['planned'] }}</span>
                            </p>
                        </div>
                        <p class="truncate text-xs text-gray-500">
                            @if ($p['address']){{ $p['address'] }} · @endif{{ $p['rate'] }}% terlaksana
                        </p>
                        <div class="relative mt-1.5 h-2.5 overflow-hidden rounded-full bg-gray-100">
                            <div class="absolute inset-y-0 left-0 rounded-full bg-blue-200" style="width: {{ $p['planned_bar'] }}%"></div>
                            <div class="absolute inset-y-0 left-0 rounded-full bg-green-500" style="width: {{ $p['visited_bar'] }}%"></div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="py-8 text-center text-sm text-gray-500">Belum ada tempat dalam rencana perjalanan.</li>
            @endforelse
        </ol>
    </section>
</div>

{{-- ================= 5. KATEGORI & PROVINSI ================= --}}
<div class="mt-6 grid gap-6 lg:grid-cols-2">

    {{-- Kategori --}}
    <section class="rounded-xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">🧭 Jenis Tempat yang Disimpan</h2>
        <p class="mb-5 text-xs text-gray-500">💡 Satu lingkaran = 100% favorit. Tiap warna adalah satu kategori.</p>

        @if ($categoryData->isNotEmpty())
            <div class="flex flex-col items-center gap-6 sm:flex-row">
                <div class="relative h-56 w-56 shrink-0">
                    <canvas id="chartCategory"></canvas>
                </div>

                <ul class="w-full space-y-3 text-sm">
                    @foreach ($categoryData as $c)
                        <li class="flex items-center justify-between gap-3">
                            <span class="flex items-center gap-2">
                                <span class="h-3 w-3 shrink-0 rounded-full" style="background: {{ $c['color'] }}"></span>
                                {{ $c['label'] }}
                            </span>
                            <span class="shrink-0">
                                <b>{{ $c['value'] }}</b>
                                <span class="text-gray-400">· {{ $c['percent'] }}%</span>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <p class="py-12 text-center text-sm text-gray-500">Belum ada data.</p>
        @endif
    </section>

    {{-- Provinsi --}}
    <section class="rounded-xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">📍 Provinsi Paling Diminati</h2>
        <p class="mb-5 text-xs text-gray-500">💡 8 provinsi dengan favorit terbanyak, lengkap dengan persentasenya.</p>

        <ul class="space-y-3.5">
            @forelse ($provinceData as $p)
                <li>
                    <div class="mb-1 flex items-baseline justify-between gap-2 text-sm">
                        <span class="truncate font-medium">{{ $p['label'] }}</span>
                        <span class="shrink-0">
                            <b class="text-blue-600">{{ $p['value'] }}</b>
                            <span class="text-gray-400">· {{ $p['percent'] }}%</span>
                        </span>
                    </div>
                    <div class="h-2.5 overflow-hidden rounded-full bg-gray-100">
                        <div class="h-full rounded-full bg-blue-500" style="width: {{ $p['bar'] }}%"></div>
                    </div>
                </li>
            @empty
                <li class="py-12 text-center text-sm text-gray-500">Belum ada data.</li>
            @endforelse
        </ul>
    </section>
</div>

{{-- ================= 6. TREN ================= --}}
<section class="mt-6 rounded-xl bg-white p-6 shadow-sm">
    <h2 class="text-lg font-semibold">📈 Tren Favorit Baru per Bulan</h2>
    <p class="mb-5 text-xs text-gray-500">💡 Titik tertinggi menandakan bulan paling ramai. Titik merah besar = bulan puncak.</p>

    <div class="mb-5 grid gap-3 sm:grid-cols-3">
        <div class="rounded-lg bg-gray-50 p-3">
            <p class="text-xs text-gray-500">Total 12 bulan terakhir</p>
            <p class="text-xl font-bold">{{ number_format($trend['total']) }} <span class="text-sm font-normal text-gray-400">favorit baru</span></p>
        </div>

        <div class="rounded-lg bg-gray-50 p-3">
            <p class="text-xs text-gray-500">Bulan paling ramai</p>
            @if ($trend['peak_label'])
                <p class="text-xl font-bold">{{ $trend['peak_label'] }} <span class="text-sm font-normal text-gray-400">({{ $trend['peak_value'] }})</span></p>
            @else
                <p class="text-sm text-gray-500">Belum ada data</p>
            @endif
        </div>

        <div class="rounded-lg bg-gray-50 p-3">
            <p class="text-xs text-gray-500">Bulan ini</p>
            <p class="text-xl font-bold">
                {{ $trend['this_month'] }}
                @if ($change === null)
                    <span class="text-sm font-normal text-gray-400">bulan lalu belum ada pembanding</span>
                @elseif ($change > 0)
                    <span class="text-sm font-semibold text-green-600">▲ naik {{ $change }}% dari bulan lalu</span>
                @elseif ($change < 0)
                    <span class="text-sm font-semibold text-red-600">▼ turun {{ abs($change) }}% dari bulan lalu</span>
                @else
                    <span class="text-sm font-normal text-gray-500">sama seperti bulan lalu</span>
                @endif
            </p>
        </div>
    </div>

    <div class="relative h-72"><canvas id="chartTrend"></canvas></div>
</section>

{{-- ================= 7. TABEL LENGKAP ================= --}}
<details class="mt-6 rounded-xl bg-white p-6 shadow-sm">
    <summary class="cursor-pointer text-lg font-semibold">📋 Lihat tabel data lengkap (Top 20)</summary>

    <div class="mt-5 grid gap-8 lg:grid-cols-2">
        <div>
            <h3 class="mb-3 font-semibold">Paling Banyak Difavoritkan</h3>
            <table class="w-full text-left text-sm">
                <thead class="text-xs uppercase text-gray-500">
                    <tr>
                        <th class="py-2">#</th>
                        <th class="py-2">Tempat</th>
                        <th class="py-2">Kategori</th>
                        <th class="py-2 text-center">Favorit</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($favorites as $i => $p)
                    <tr class="border-t">
                        <td class="py-2 text-gray-400">{{ $i + 1 }}</td>
                        <td class="py-2">
                            <p class="font-medium">{{ $p->name }}</p>
                            <p class="text-xs text-gray-500">{{ collect([$p->city, $p->province])->filter()->implode(', ') }}</p>
                        </td>
                        <td class="py-2">{{ $p->category ?? '—' }}</td>
                        <td class="py-2 text-center font-semibold text-red-600">{{ $p->total }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-6 text-center text-gray-500">Belum ada data.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div>
            <h3 class="mb-3 font-semibold">Paling Sering Dikunjungi</h3>
            <table class="w-full text-left text-sm">
                <thead class="text-xs uppercase text-gray-500">
                    <tr>
                        <th class="py-2">#</th>
                        <th class="py-2">Tempat</th>
                        <th class="py-2 text-center">Dikunjungi</th>
                        <th class="py-2 text-center">Direncanakan</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($visited as $i => $p)
                    <tr class="border-t">
                        <td class="py-2 text-gray-400">{{ $i + 1 }}</td>
                        <td class="py-2">
                            <p class="font-medium">{{ $p->name }}</p>
                            @if ($p->address)<p class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($p->address, 60) }}</p>@endif
                        </td>
                        <td class="py-2 text-center font-semibold text-green-600">{{ (int) $p->visited_count }}</td>
                        <td class="py-2 text-center">{{ (int) $p->planned_count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-6 text-center text-gray-500">Belum ada data.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</details>

{{-- ================= CHART.JS (donat & tren) ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    if (typeof Chart === 'undefined') {
        document.querySelectorAll('canvas').forEach(function (c) {
            c.parentElement.innerHTML =
                '<p class="py-12 text-center text-sm text-red-600">Grafik gagal dimuat. Periksa koneksi internet.</p>';
        });
        return;
    }

    const data = @json($charts);

    Chart.defaults.font.family = 'Arial, Helvetica, sans-serif';
    Chart.defaults.color = '#475569';

    /* ---------- DONAT KATEGORI (total di tengah) ---------- */
    const elCat = document.getElementById('chartCategory');
    if (elCat) {
        const centerText = {
            id: 'centerText',
            afterDraw: function (chart) {
                const area = chart.chartArea;
                const x = (area.left + area.right) / 2;
                const y = (area.top + area.bottom) / 2;
                const ctx = chart.ctx;
                ctx.save();
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillStyle = '#0f172a';
                ctx.font = 'bold 28px Arial';
                ctx.fillText(data.totalFavorites, x, y - 9);
                ctx.fillStyle = '#64748b';
                ctx.font = '12px Arial';
                ctx.fillText('favorit', x, y + 15);
                ctx.restore();
            }
        };

        new Chart(elCat, {
            type: 'doughnut',
            data: {
                labels: data.category.labels,
                datasets: [{
                    data: data.category.values,
                    backgroundColor: data.category.colors,
                    borderColor: '#ffffff',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ' ' + ctx.label + ': ' + ctx.parsed + ' favorit (' +
                                    data.category.percents[ctx.dataIndex] + '%)';
                            }
                        }
                    }
                }
            },
            plugins: [centerText]
        });
    }

    /* ---------- TREN BULANAN (angka di tiap titik, puncak ditandai) ---------- */
    const elTrend = document.getElementById('chartTrend');
    if (elTrend) {
        const values = data.trend.values;
        const peak = data.trend.peak;
        const peakIndex = peak > 0 ? values.indexOf(peak) : -1;

        const valueLabels = {
            id: 'valueLabels',
            afterDatasetsDraw: function (chart) {
                const ctx = chart.ctx;
                const meta = chart.getDatasetMeta(0);
                ctx.save();
                ctx.font = 'bold 11px Arial';
                ctx.textAlign = 'center';
                meta.data.forEach(function (point, i) {
                    const v = values[i];
                    if (v > 0) {
                        ctx.fillStyle = i === peakIndex ? '#b91c1c' : '#334155';
                        ctx.fillText(v, point.x, point.y - 12);
                    }
                });
                ctx.restore();
            }
        };

        new Chart(elTrend, {
            type: 'line',
            data: {
                labels: data.trend.labels,
                datasets: [{
                    label: 'Favorit baru',
                    data: values,
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.10)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 3,
                    pointRadius: values.map(function (_, i) { return i === peakIndex ? 8 : 4; }),
                    pointBackgroundColor: values.map(function (_, i) { return i === peakIndex ? '#b91c1c' : '#ffffff'; }),
                    pointBorderColor: '#dc2626',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) { return ' ' + ctx.parsed.y + ' favorit baru'; }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        beginAtZero: true,
                        grace: '20%',
                        ticks: { precision: 0 },
                        title: { display: true, text: 'Jumlah favorit baru' }
                    }
                }
            },
            plugins: [valueLabels]
        });
    }
})();
</script>

@endif
@endsection