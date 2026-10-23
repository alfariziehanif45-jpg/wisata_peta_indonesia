<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FavoritePlace;
use App\Models\TravelPlanPlace;
use Illuminate\Support\Str;

class PlaceController extends Controller
{
    /** Kategori resmi: [label, warna] (warna sama dengan marker di peta). */
    private const CATEGORIES = [
        'wisata'  => ['Wisata',        '#2e7d32'],
        'kuliner' => ['Kuliner',       '#e65100'],
        'hotel'   => ['Hotel',         '#6a1b9a'],
        'ibadah'  => ['Tempat Ibadah', '#00838f'],
    ];

    public function index()
    {
        /* ---------------- ANGKA UTAMA ---------------- */

        $totalFavorites = FavoritePlace::count();
        $totalPlanned   = TravelPlanPlace::count();
        $totalVisited   = TravelPlanPlace::where('visited', true)->count();

        $summary = [
            'unique_favorites' => FavoritePlace::query()->distinct()->count('place_key'),
            'total_favorites'  => $totalFavorites,
            'total_planned'    => $totalPlanned,
            'total_visited'    => $totalVisited,
            'not_visited'      => max($totalPlanned - $totalVisited, 0),
            'conversion'       => $this->percent($totalVisited, $totalPlanned),
        ];

        /* ---------------- DATA TABEL (Top 20) ---------------- */

        $favorites = FavoritePlace::query()
            ->selectRaw('place_key, MAX(name) as name, MAX(city) as city, MAX(province) as province, MAX(category) as category, COUNT(*) as total')
            ->groupBy('place_key')
            ->orderByDesc('total')
            ->limit(20)
            ->get();

        $visited = TravelPlanPlace::query()
            ->selectRaw('place_key, MAX(name) as name, MAX(address) as address, COUNT(*) as planned_count, SUM(CASE WHEN visited = 1 THEN 1 ELSE 0 END) as visited_count')
            ->groupBy('place_key')
            ->orderByDesc('visited_count')
            ->orderByDesc('planned_count')
            ->limit(20)
            ->get();

        /* ---------------- PERINGKAT FAVORIT (Top 10) ---------------- */

        $maxFav = (int) ($favorites->first()?->total ?? 0);

        $topFavorites = $favorites->take(10)->map(fn ($p) => [
            'name'     => (string) $p->name,
            'location' => collect([$p->city, $p->province])->filter()->implode(', '),
            'category' => $this->categoryLabel($p->category),
            'total'    => (int) $p->total,
            'share'    => $this->percent((int) $p->total, $totalFavorites, 1),
            'bar'      => $this->percent((int) $p->total, $maxFav),
        ])->values();

        /* ---------------- PERINGKAT DIKUNJUNGI (Top 10) ---------------- */

        $topVisitedRaw = $visited->take(10)->map(fn ($p) => [
            'name'    => (string) $p->name,
            'address' => Str::limit((string) $p->address, 60),
            'planned' => (int) $p->planned_count,
            'visited' => (int) $p->visited_count,
        ]);

        $maxPlanned = max((int) ($topVisitedRaw->max('planned') ?? 0), 1);

        $topVisited = $topVisitedRaw->map(fn ($p) => $p + [
            'rate'        => $this->percent($p['visited'], $p['planned']),
            'planned_bar' => $this->percent($p['planned'], $maxPlanned),
            'visited_bar' => $this->percent($p['visited'], $maxPlanned),
        ])->values();

        /* ---------------- KATEGORI ---------------- */

        $categoryData = FavoritePlace::query()
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->get()
            ->groupBy(fn ($row) => strtolower(trim((string) $row->category)) ?: 'lainnya')
            ->map(fn ($rows) => (int) $rows->sum('total'))
            ->sortDesc()
            ->map(function ($total, $key) use ($totalFavorites) {
                [$label, $color] = self::CATEGORIES[$key] ?? [Str::title($key), '#64748b'];

                return [
                    'label'   => $label,
                    'color'   => $color,
                    'value'   => $total,
                    'percent' => $this->percent($total, $totalFavorites, 1),
                ];
            })
            ->values();

        /* ---------------- PROVINSI (Top 8) ---------------- */

        $provinces = FavoritePlace::query()
            ->selectRaw('province, COUNT(*) as total')
            ->groupBy('province')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $maxProv = (int) ($provinces->first()?->total ?? 0);

        $provinceData = $provinces->map(fn ($r) => [
            'label'   => $r->province ?: 'Tidak diketahui',
            'value'   => (int) $r->total,
            'percent' => $this->percent((int) $r->total, $totalFavorites, 1),
            'bar'     => $this->percent((int) $r->total, $maxProv),
        ])->values();

        /* ---------------- TREN 12 BULAN ---------------- */

        $start  = now()->startOfMonth()->subMonths(11);
        $counts = FavoritePlace::query()
            ->whereNotNull('created_at')
            ->where('created_at', '>=', $start)
            ->get(['created_at'])
            ->countBy(fn ($f) => $f->created_at->format('Y-m'));

        $trendLabels = [];
        $trendValues = [];
        for ($i = 0; $i < 12; $i++) {
            $month         = $start->copy()->addMonths($i);
            $trendLabels[] = $month->copy()->locale('id')->translatedFormat('M Y');
            $trendValues[] = (int) ($counts[$month->format('Y-m')] ?? 0);
        }

        $peakValue = max($trendValues);
        $thisMonth = $trendValues[11];
        $lastMonth = $trendValues[10];

        $trend = [
            'labels'     => $trendLabels,
            'values'     => $trendValues,
            'total'      => array_sum($trendValues),
            'peak_label' => $peakValue > 0 ? $trendLabels[array_search($peakValue, $trendValues)] : null,
            'peak_value' => $peakValue,
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
            'change'     => $lastMonth > 0
                ? (int) round((($thisMonth - $lastMonth) / $lastMonth) * 100)
                : null,
        ];

        /* ---------------- RINGKASAN DALAM KALIMAT ---------------- */

        $insights = [];

        if ($topFavorites->isNotEmpty()) {
            $top = $topFavorites->first();
            $insights[] = [
                'icon'  => '❤️',
                'title' => 'Paling difavoritkan',
                'text'  => "{$top['name']} — disimpan {$top['total']} pengguna ({$top['share']}% dari semua favorit).",
            ];
        }

        if ($categoryData->isNotEmpty()) {
            $cat = $categoryData->first();
            $insights[] = [
                'icon'  => '🧭',
                'title' => 'Kategori terpopuler',
                'text'  => "{$cat['label']} — {$cat['value']} favorit ({$cat['percent']}%).",
            ];
        }

        if ($provinceData->isNotEmpty()) {
            $prov = $provinceData->first();
            $insights[] = [
                'icon'  => '📍',
                'title' => 'Provinsi terpopuler',
                'text'  => "{$prov['label']} — {$prov['value']} favorit ({$prov['percent']}%).",
            ];
        }

        if ($totalPlanned > 0) {
            $insights[] = [
                'icon'  => '✅',
                'title' => 'Rencana terlaksana',
                'text'  => "{$totalVisited} dari {$totalPlanned} tempat dalam rencana perjalanan sudah dikunjungi ({$summary['conversion']}%).",
            ];
        }

        /* ---------------- DATA UNTUK CHART.JS ---------------- */

        $charts = [
            'totalFavorites' => $totalFavorites,
            'category' => [
                'labels'   => $categoryData->pluck('label')->values(),
                'values'   => $categoryData->pluck('value')->values(),
                'colors'   => $categoryData->pluck('color')->values(),
                'percents' => $categoryData->pluck('percent')->values(),
            ],
            'trend' => [
                'labels' => $trend['labels'],
                'values' => $trend['values'],
                'peak'   => $trend['peak_value'],
            ],
        ];

        return view('admin.places.index', compact(
            'summary', 'insights', 'topFavorites', 'topVisited',
            'categoryData', 'provinceData', 'trend', 'charts',
            'favorites', 'visited'
        ));
    }

    /** Persentase aman (tidak error kalau pembagi 0). */
    private function percent($part, $whole, int $decimals = 0)
    {
        return (float) $whole > 0 ? round(($part / $whole) * 100, $decimals) : 0;
    }

    private function categoryLabel(?string $category): string
    {
        $key = strtolower(trim((string) $category));

        if ($key === '') {
            return '—';
        }

        return self::CATEGORIES[$key][0] ?? Str::title($key);
    }
}