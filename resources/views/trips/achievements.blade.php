<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Pencapaian Penjelajah</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;800&display=swap" rel="stylesheet">
<style>
:root{--ink:#10283a;--muted:#5f7586;--bg:#f2f7f9;--surface:#fff;--line:#dce7ec;--brand:#0b8f9c;--brand-d:#08707a;--brand-soft:#dff3f4;--go:#16a368;--gold:#f2a516;--gold-soft:#fff3d6;--radius:18px}
@media (prefers-color-scheme:dark){:root{--ink:#e8f1f5;--muted:#93a9b7;--bg:#0b1820;--surface:#122530;--line:#21394a;--brand:#3cc4d0;--brand-d:#6fd6df;--brand-soft:#123a42;--go:#3dd696;--gold-soft:#3a2f12}}
*{box-sizing:border-box}
body{margin:0;font-family:'Plus Jakarta Sans',system-ui,Arial,sans-serif;background:var(--bg);color:var(--ink);line-height:1.55}
.wrap{max-width:860px;margin:auto;padding:24px 18px 64px}
.back{text-decoration:none;font-weight:600;font-size:14px;color:var(--muted)}
.back:hover{color:var(--ink)}
h1{font-size:clamp(28px,5vw,40px);font-weight:800;letter-spacing:-.02em;margin:18px 0 4px}
.lead{color:var(--muted);margin:0 0 22px}
.alert{padding:12px 16px;border-radius:12px;margin:0 0 16px;font-size:14px;font-weight:500;background:#dcf5e8;color:#0e6b43}
.stats{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin:0 0 26px}
.stat{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);padding:18px 20px}
.stat.hl{background:linear-gradient(135deg,var(--gold-soft),var(--surface));border-color:var(--gold)}
.stat b{display:block;font-size:36px;font-weight:800;line-height:1.1;letter-spacing:-.02em}
.stat span{font-size:14px;color:var(--muted)}
.list{display:grid;gap:18px}
.card{display:flex;gap:18px;background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);padding:20px;box-shadow:0 8px 24px -16px rgba(16,40,58,.25)}
@media(max-width:520px){.card{flex-direction:column}}
.seal{flex:none;width:76px;height:76px;border-radius:50%;display:grid;place-items:center;font-size:34px;background:radial-gradient(circle at 30% 25%,#ffe08a,var(--gold));border:3px dashed rgba(59,42,0,.35);outline:4px solid var(--gold-soft);transform:rotate(-8deg)}
.card h2{margin:0;font-size:20px;font-weight:800;letter-spacing:-.01em}
.when{margin:2px 0 0;color:var(--muted);font-size:13px}
.count{margin:10px 0 8px;font-size:14px;font-weight:600;color:var(--go)}
.chips{display:flex;flex-wrap:wrap;gap:6px;margin:0;padding:0;list-style:none}
.chips li{padding:4px 11px;border-radius:99px;background:var(--brand-soft);color:var(--brand-d);font-size:13px;font-weight:500}
.chips li::before{content:"✓ ";font-weight:800}
.none{text-align:center;padding:44px 20px;color:var(--muted);border:2px dashed var(--line);border-radius:var(--radius)}
.none .big{font-size:40px;display:block;margin-bottom:6px;filter:grayscale(1);opacity:.5}
.cta{display:inline-block;margin-top:14px;padding:10px 18px;border-radius:12px;background:var(--brand);color:#fff;font-weight:600;font-size:14px;text-decoration:none}
.cta:hover{background:var(--brand-d)}
.cta:focus-visible,.back:focus-visible{outline:3px solid var(--brand);outline-offset:2px}
</style>
</head>
<body>
<main class="wrap">
  <a class="back" href="{{ route('profile.show') }}">← Kembali ke profil</a>
  <h1>Pencapaian Penjelajah</h1>
  <p class="lead">Setiap perjalanan yang selesai tercatat di sini sebagai stempel.</p>

  @if(session('success'))<div class="alert">{{ session('success') }}</div>@endif

  <section class="stats">
    <div class="stat"><b>{{ $totalTrips }}</b><span>Rencana dibuat</span></div>
    <div class="stat hl"><b>{{ $completedTrips->count() }}</b><span>Perjalanan selesai</span></div>
  </section>

  <section class="list">
    @forelse($completedTrips as $trip)
    <article class="card">
      <div class="seal" aria-hidden="true">🏆</div>
      <div>
        <h2>{{ $trip->title }}</h2>
        <p class="when">Diselesaikan {{ $trip->completed_at?->translatedFormat('d F Y, H:i') }}</p>
        <p class="count">{{ $trip->places_count }} tujuan wisata terselesaikan</p>
        <ul class="chips">
          @foreach($trip->places as $place)<li>{{ $place->name }}</li>@endforeach
        </ul>
      </div>
    </article>
    @empty
    <div class="none">
      <span class="big">🏆</span>
      Belum ada pencapaian. Selesaikan rencana perjalanan untuk mendapat stempel pertamamu.
      <br><a class="cta" href="{{ route('trips.index') }}">Lihat rencana perjalanan</a>
    </div>
    @endforelse
  </section>
</main>
</body>
</html>