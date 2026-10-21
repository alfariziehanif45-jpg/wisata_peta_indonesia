<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Rencana Perjalanan</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;800&display=swap" rel="stylesheet">
<style>
:root{--ink:#10283a;--muted:#5f7586;--bg:#f2f7f9;--surface:#fff;--line:#dce7ec;--brand:#0b8f9c;--brand-d:#08707a;--brand-soft:#dff3f4;--go:#16a368;--gold:#f2a516;--danger:#d63c3c;--radius:18px}
@media (prefers-color-scheme:dark){:root{--ink:#e8f1f5;--muted:#93a9b7;--bg:#0b1820;--surface:#122530;--line:#21394a;--brand:#3cc4d0;--brand-d:#6fd6df;--brand-soft:#123a42;--go:#3dd696;--danger:#ff6b6b}}
*{box-sizing:border-box}
body{margin:0;font-family:'Plus Jakarta Sans',system-ui,Arial,sans-serif;background:var(--bg);color:var(--ink);line-height:1.55}
a{color:var(--brand-d)}
.wrap{max-width:860px;margin:auto;padding:24px 18px 64px}
.top{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px}
.back{text-decoration:none;font-weight:600;font-size:14px;color:var(--muted)}
.back:hover{color:var(--ink)}
h1{font-size:clamp(28px,5vw,40px);font-weight:800;letter-spacing:-.02em;margin:18px 0 4px}
.lead{color:var(--muted);margin:0 0 22px}
.alert{padding:12px 16px;border-radius:12px;margin:0 0 16px;font-size:14px;font-weight:500}
.alert.ok{background:#dcf5e8;color:#0e6b43}
.alert.err{background:#fde4e4;color:#a02727}
.alert.err p{margin:2px 0}
.panel{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);padding:0;margin:0 0 20px;box-shadow:0 8px 24px -16px rgba(16,40,58,.25)}
details>summary{list-style:none;cursor:pointer;padding:16px 20px;font-weight:700;display:flex;align-items:center;gap:10px;border-radius:var(--radius)}
details>summary::-webkit-details-marker{display:none}
details>summary::before{content:"+";display:grid;place-items:center;width:26px;height:26px;border-radius:50%;background:var(--brand);color:#fff;font-size:18px;line-height:1;transition:transform .2s}
details[open]>summary::before{transform:rotate(45deg)}
.formbody{padding:4px 20px 20px}
label{display:block;font-weight:600;font-size:13px;margin:12px 0 5px}
input,textarea{width:100%;padding:11px 13px;border:1.5px solid var(--line);border-radius:12px;background:var(--bg);color:var(--ink);font:inherit;transition:border-color .15s,box-shadow .15s}
input:focus,textarea:focus{outline:none;border-color:var(--brand);box-shadow:0 0 0 4px var(--brand-soft)}
textarea{min-height:84px;resize:vertical}
.row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
@media(max-width:560px){.row{grid-template-columns:1fr}}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:10px 16px;border-radius:12px;border:1.5px solid var(--line);background:var(--surface);color:var(--ink);font:inherit;font-weight:600;font-size:14px;cursor:pointer;text-decoration:none;transition:background .15s,transform .1s}
.btn:hover{background:var(--brand-soft)}
.btn:active{transform:scale(.97)}
.btn:focus-visible,summary:focus-visible{outline:3px solid var(--brand);outline-offset:2px}
.btn.primary{background:var(--brand);border-color:var(--brand);color:#fff}
.btn.primary:hover{background:var(--brand-d)}
.btn.done{background:var(--go);border-color:var(--go);color:#fff}
.btn.danger{color:var(--danger);border-color:transparent;background:transparent}
.btn.danger:hover{background:#fde4e4}
.btn[disabled]{opacity:.45;cursor:not-allowed}
.btn.sm{padding:7px 12px;font-size:13px}
.trip{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);margin:0 0 22px;overflow:hidden;box-shadow:0 8px 24px -16px rgba(16,40,58,.25)}
.trip.finished{border-color:var(--gold)}
.hero{display:flex;gap:20px;align-items:center;padding:22px;background:linear-gradient(120deg,var(--brand-soft),transparent 70%)}
.ring{--p:0;flex:none;width:84px;height:84px;border-radius:50%;background:conic-gradient(var(--go) calc(var(--p)*1%),var(--line) 0);display:grid;place-items:center}
.ring span{width:66px;height:66px;border-radius:50%;background:var(--surface);display:grid;place-items:center;font-weight:800;font-size:18px}
.finished .ring{background:conic-gradient(var(--gold) 100%,var(--gold) 0)}
.hero h2{margin:0;font-size:22px;font-weight:800;letter-spacing:-.01em}
.badge{display:inline-block;margin-left:6px;padding:3px 10px;border-radius:99px;background:var(--gold);color:#3b2a00;font-size:12px;font-weight:700;vertical-align:middle}
.meta{margin:4px 0 0;color:var(--muted);font-size:14px}
.notes{margin:8px 0 0;font-size:14px}
.body{padding:6px 22px 22px}
.sub{font-size:14px;font-weight:700;margin:16px 0 8px}
.stops{list-style:none;margin:0;padding:0}
.stop{position:relative;display:flex;gap:14px;align-items:flex-start;padding:0 0 18px 0}
.stop:last-child{padding-bottom:0}
.stop::before{content:"";position:absolute;left:13px;top:28px;bottom:-2px;width:2px;background:var(--line)}
.stop:last-child::before{display:none}
.dot{flex:none;width:28px;height:28px;border-radius:50%;border:2px solid var(--line);background:var(--surface);display:grid;place-items:center;font-size:13px;font-weight:800;color:transparent;z-index:1}
.stop.seen .dot{background:var(--go);border-color:var(--go);color:#fff}
.stop.seen::before{background:var(--go)}
.stop .info{flex:1;min-width:0}
.stop strong{display:block}
.stop.seen strong{color:var(--muted)}
.addr{font-size:13px;color:var(--muted)}
.maplink{font-size:13px;font-weight:600}
.empty{color:var(--muted);font-size:14px;padding:10px 0}
.nest{border:1px dashed var(--line);border-radius:14px;margin:14px 0 4px;box-shadow:none}
.foot{display:flex;gap:8px;flex-wrap:wrap;justify-content:space-between;align-items:center;margin-top:18px;padding-top:16px;border-top:1px solid var(--line)}
.foot form{margin:0}
.hint{font-size:12px;color:var(--muted);margin:0}
.none{text-align:center;padding:40px 20px;color:var(--muted);border:2px dashed var(--line);border-radius:var(--radius)}
</style>
</head>
<body>
<main class="wrap">
  <div class="top"><a class="back" href="{{ route('profile.show') }}">← Kembali ke profil</a></div>
  <h1>Rencana Perjalanan</h1>
  <p class="lead">Susun tujuan, tandai yang sudah dikunjungi, dan selesaikan perjalananmu.</p>

  @if(session('success'))<div class="alert ok">{{ session('success') }}</div>@endif
  @if($errors->any())<div class="alert err">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif

  <section class="panel">
    <details {{ $plans->isEmpty() ? 'open' : '' }}>
      <summary>Buat rencana baru</summary>
      <form class="formbody" method="POST" action="{{ route('trips.store') }}">@csrf
        <label for="title">Nama perjalanan</label>
        <input id="title" name="title" required maxlength="255" placeholder="Contoh: Liburan ke Bali">
        <label for="notes">Catatan</label>
        <textarea id="notes" name="notes" placeholder="Catatan perjalanan"></textarea>
        <div class="row">
          <div><label for="start_date">Tanggal mulai</label><input id="start_date" type="date" name="start_date"></div>
          <div><label for="end_date">Tanggal selesai</label><input id="end_date" type="date" name="end_date"></div>
        </div>
        <p style="margin:16px 0 0"><button class="btn primary">Buat rencana</button></p>
      </form>
    </details>
  </section>

  @forelse($plans as $trip)
  <article class="trip {{ $trip->completed_at ? 'finished' : '' }}">
    <header class="hero">
      <div class="ring" style="--p:{{ $trip->progress }}"><span>{{ $trip->completed_at ? '🏆' : $trip->progress.'%' }}</span></div>
      <div>
        <h2>{{ $trip->title }} @if($trip->completed_at)<span class="badge">Selesai</span>@endif</h2>
        <p class="meta">{{ $trip->start_date?->format('d M Y') ?? 'Tanggal belum ditentukan' }}@if($trip->end_date) – {{ $trip->end_date->format('d M Y') }}@endif</p>
        <p class="meta">{{ $trip->visited_places_count }} dari {{ $trip->places_count }} tujuan dikunjungi</p>
        @if($trip->notes)<p class="notes">{{ $trip->notes }}</p>@endif
      </div>
    </header>

    <div class="body">
      @if(!$trip->completed_at)
      <section class="panel nest">
        <details>
          <summary>Tambah tujuan wisata</summary>
          <form class="formbody" method="POST" action="{{ route('trips.places.store',$trip) }}">@csrf
            <label>Nama tempat</label>
            <input name="name" required placeholder="Nama tempat dari peta">
            <label>Alamat</label>
            <input name="address" placeholder="Alamat tempat">
            <label>Link peta (opsional)</label>
            <input type="url" name="url" placeholder="https://maps.google.com/...">
            <p style="margin:16px 0 0"><button class="btn primary sm">Tambah tujuan</button></p>
          </form>
        </details>
      </section>
      @endif

      <h3 class="sub">Rute tujuan</h3>
      @if($trip->places->isEmpty())
        <p class="empty">Belum ada tujuan. Tambahkan tempat wisata pertamamu di atas.</p>
      @else
      <ol class="stops">
        @foreach($trip->places as $place)
        <li class="stop {{ $place->visited ? 'seen' : '' }}">
          <span class="dot">✓</span>
          <div class="info">
            <strong>{{ $place->name }}</strong>
            @if($place->address)<div class="addr">{{ $place->address }}</div>@endif
            @if($place->url)<a class="maplink" href="{{ $place->url }}" target="_blank" rel="noopener">Buka peta</a>@endif
          </div>
          @if(!$trip->completed_at)
          <form method="POST" action="{{ route('trips.places.toggle',[$trip,$place]) }}">@csrf @method('PATCH')
            <button class="btn sm {{ $place->visited ? 'done' : '' }}">{{ $place->visited ? '✓ Sudah dikunjungi (batalkan)' : 'Tandai dikunjungi' }}</button>
          </form>
          @else
          <span class="addr">✓ Selesai</span>
          @endif
        </li>
        @endforeach
      </ol>
      @endif

      @if(!$trip->completed_at)
      <div class="foot">
        <form method="POST" action="{{ route('trips.destroy',$trip) }}" onsubmit="return confirm('Hapus rencana ini?')">@csrf @method('DELETE')
          <button class="btn danger sm">Hapus rencana</button>
        </form>
        <form method="POST" action="{{ route('trips.complete',$trip) }}">@csrf
          <button class="btn primary" {{ $trip->places_count === 0 || $trip->visited_places_count !== $trip->places_count ? 'disabled' : '' }}>Tandai perjalanan selesai</button>
        </form>
      </div>
      <p class="hint" style="text-align:right;margin-top:6px">Tandai semua tujuan dikunjungi untuk menyelesaikan perjalanan.</p>
      @endif
    </div>
  </article>
  @empty
  <div class="none">Belum ada rencana perjalanan. Buat yang pertama lewat form di atas.</div>
  @endforelse
</main>
</body>
</html>