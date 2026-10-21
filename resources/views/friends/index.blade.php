<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Teman - Jelajah Wisata Indonesia</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{
    --red:#dc2626;--red-dark:#b91c1c;--red-deep:#7f1d1d;--red-soft:#fef2f2;--red-line:#fecaca;
    --gold:#f59e0b;--green:#16a34a;--green-dark:#15803d;--green-soft:#f0fdf4;
    --ink:#1c1917;--muted:#78716c;--line:#ece8e4;--bg:#faf8f6;--card:#fff;
    --shadow:0 1px 2px rgba(28,25,23,.04),0 10px 30px rgba(28,25,23,.07);
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;min-height:100vh;background:var(--bg);color:var(--ink);font-family:"Plus Jakarta Sans","Segoe UI",system-ui,-apple-system,Arial,sans-serif;-webkit-font-smoothing:antialiased;line-height:1.5}
.container{max-width:780px;margin:auto;padding:16px 16px 64px}

/* ===== HERO ===== */
.hero{
    position:relative;overflow:hidden;border-radius:28px;color:#fff;padding:26px 28px 30px;margin-bottom:22px;
    background:
        radial-gradient(circle at 1px 1px,rgba(255,255,255,.13) 1.4px,transparent 1.6px) 0 0/16px 16px,
        linear-gradient(135deg,var(--red-deep) 0%,var(--red-dark) 45%,var(--red) 100%);
    box-shadow:0 18px 40px rgba(127,29,29,.28);
}
.hero::before,.hero::after{content:"";position:absolute;border:2px solid rgba(255,255,255,.14);transform:rotate(45deg);border-radius:18px;pointer-events:none}
.hero::before{width:210px;height:210px;right:-70px;top:-80px}
.hero::after{width:120px;height:120px;right:40px;top:30px;border-color:rgba(245,158,11,.45)}
.hero>*{position:relative;z-index:1}
.back{display:inline-flex;align-items:center;gap:6px;color:rgba(255,255,255,.85);text-decoration:none;font-weight:600;font-size:14px;margin-bottom:18px;padding:6px 12px 6px 8px;border-radius:99px;background:rgba(255,255,255,.12);transition:background .15s}
.back:hover{background:rgba(255,255,255,.22);color:#fff}
.back svg{width:16px;height:16px}
h1{font-size:clamp(30px,6vw,40px);line-height:1.1;margin:0 0 8px;letter-spacing:-.03em;font-weight:800}
.lead{margin:0 0 22px;max-width:46ch;color:rgba(255,255,255,.88);font-size:15px}

.search{display:flex;gap:8px;padding:6px;background:#fff;border-radius:18px;box-shadow:0 10px 24px rgba(0,0,0,.18)}
.search .field{position:relative;flex:1;min-width:0;display:flex;align-items:center}
.search .field svg{position:absolute;left:14px;width:19px;height:19px;color:var(--muted);pointer-events:none}
.search input{width:100%;padding:13px 14px 13px 42px;border:0;border-radius:12px;background:transparent;font:inherit;font-size:15px;color:var(--ink)}
.search input:focus{outline:none;background:var(--red-soft)}
.search input::placeholder{color:#a8a29e}
.hint{font-size:13px;color:rgba(255,255,255,.9);margin:12px 4px 0}

.stats{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}
.stat{display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;padding:7px 14px;border-radius:99px;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.2)}
.stat b{font-size:15px;font-weight:800}
.stat.alert{background:var(--gold);border-color:var(--gold);color:#451a03}

/* ===== ALERTS ===== */
.success,.errors{display:flex;gap:10px;padding:13px 18px;border-radius:14px;margin-bottom:18px;font-size:14px;font-weight:500}
.success{background:var(--green-soft);border:1px solid #bbf7d0;color:#166534}
.errors{background:var(--red-soft);border:1px solid var(--red-line);color:#991b1b;flex-direction:column;gap:2px}
.errors p{margin:0}

/* ===== PANELS ===== */
.panel{background:var(--card);border-radius:24px;box-shadow:var(--shadow);padding:22px 22px 24px;margin-bottom:20px}
.panel.incoming{border:1.5px solid var(--red-line);background:linear-gradient(180deg,#fff7f7 0,#fff 90px)}
.panel-head{display:flex;align-items:center;gap:10px;margin:0 0 16px}
.panel-head h2{font-size:18px;margin:0;letter-spacing:-.01em;font-weight:800}
.count{font-size:12px;font-weight:800;background:var(--red-soft);color:var(--red-dark);padding:3px 10px;border-radius:99px}
.panel.incoming .count{background:var(--red);color:#fff}

/* ===== BUTTONS ===== */
.btn,.small-btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;font:inherit;font-weight:700;cursor:pointer;text-decoration:none;border:1px solid #e7e2dd;background:#fff;color:var(--ink);transition:transform .15s,background .15s,border-color .15s}
.btn{padding:12px 22px;font-size:14px;border-radius:12px}
.small-btn{padding:9px 14px;font-size:13px;border-radius:11px;white-space:nowrap}
.btn:hover,.small-btn:hover{background:#f5f3f1}
.btn:active,.small-btn:active{transform:scale(.96)}
.red{background:linear-gradient(135deg,var(--red),var(--red-dark));border-color:transparent;color:#fff;box-shadow:0 6px 16px rgba(220,38,38,.28)}
.red:hover{background:linear-gradient(135deg,#ef4444,var(--red))}
.ghost-red{color:var(--red)}
.ghost-red:hover{background:var(--red-soft);border-color:var(--red-line)}
.green{background:var(--green);border-color:var(--green);color:#fff}
.green:hover{background:var(--green-dark);border-color:var(--green-dark)}
.btn:focus-visible,.small-btn:focus-visible,.back:focus-visible{outline:3px solid var(--gold);outline-offset:2px}
form{margin:0}

/* ===== PERSON ===== */
.list{display:grid;gap:10px}
.person{display:flex;align-items:center;gap:14px;padding:13px 14px;border:1px solid var(--line);border-radius:18px;flex-wrap:wrap;background:#fff;transition:border-color .15s,box-shadow .15s}
.person:hover{border-color:var(--red-line);box-shadow:0 6px 18px rgba(220,38,38,.07)}
.avatar{flex:none;width:54px;height:54px;border-radius:50%;object-fit:cover;display:grid;place-items:center;font-weight:800;font-size:21px;border:2px solid #fff;box-shadow:0 0 0 2px var(--line)}
.t0{background:linear-gradient(135deg,#fee2e2,#fecaca);color:#991b1b}
.t1{background:linear-gradient(135deg,#ffedd5,#fed7aa);color:#9a3412}
.t2{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#92400e}
.t3{background:linear-gradient(135deg,#dcfce7,#bbf7d0);color:#166534}
.t4{background:linear-gradient(135deg,#e0f2fe,#bae6fd);color:#075985}
.t5{background:linear-gradient(135deg,#fae8ff,#f5d0fe);color:#86198f}
.who{flex:1;min-width:150px}
.who strong{display:block;font-size:15px;overflow-wrap:anywhere}
.who .un{color:var(--red);font-size:13px;font-weight:600;overflow-wrap:anywhere}
.who .dom{color:var(--muted);font-size:13px;margin-top:2px}
.acts{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
.state{font-size:13px;font-weight:700;color:#166534;background:var(--green-soft);padding:8px 13px;border-radius:11px}
.state.wait{color:#92400e;background:#fef3c7}

/* ===== EMPTY ===== */
.empty{text-align:center;color:var(--muted);padding:30px 18px;background:#fcfbfa;border:2px dashed #e7e2dd;border-radius:18px;line-height:1.7;font-size:14px}
.empty .ico{display:grid;place-items:center;width:52px;height:52px;margin:0 auto 10px;border-radius:50%;background:var(--red-soft);font-size:24px}
.empty strong{display:block;color:var(--ink);font-size:15px}

@media(max-width:560px){
    .container{padding:10px 10px 44px}
    .hero{padding:20px 18px 24px;border-radius:22px}
    .panel{padding:18px 14px 20px;border-radius:20px}
    .search{flex-direction:column;padding:8px}
    .search .btn{width:100%}
    .acts{width:100%}
    .acts form,.acts .small-btn{flex:1}
    .acts .small-btn{width:100%}
    .state{flex:1;text-align:center}
}
@media(prefers-reduced-motion:reduce){*{transition:none!important;scroll-behavior:auto!important}}
</style>
</head>
<body>
@php
    $initial = fn ($u) => mb_strtoupper(mb_substr($u->name ?: '?', 0, 1));
    $handle  = fn ($u) => $u->username ?: \Illuminate\Support\Str::slug($u->name, '_');
    $tone    = fn ($u) => 't' . (((int) $u->id) % 6);
@endphp
<main class="container">

    {{-- HERO + PENCARIAN --}}
    <header class="hero">
        <a class="back" href="{{ route('profile.show') }}">
            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4l-6 6 6 6"/></svg>
            Kembali ke profil
        </a>
        <h1>Teman</h1>
        <p class="lead">Cari penjelajah lain lewat username atau nama profilnya, lalu tambahkan sebagai teman perjalanan.</p>

        <form class="search" method="GET" action="{{ route('friends.index') }}" role="search">
            <label class="field">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="9" cy="9" r="6"/><path d="M14 14l4 4"/></svg>
                <input type="search" name="q" value="{{ $q }}" placeholder="Ketik username atau nama, mis. budi_traveler" autocomplete="off" maxlength="60" aria-label="Cari username atau nama">
            </label>
            <button class="btn red" type="submit">Cari</button>
        </form>

        @if($q !== '' && mb_strlen($q) < 2)
            <p class="hint">Ketik minimal 2 huruf.</p>
        @endif

        <div class="stats">
            <span class="stat"><b>{{ $friends->count() }}</b> teman</span>
            @if($incoming->isNotEmpty())
                <a class="stat alert" href="#permintaan" style="text-decoration:none"><b>{{ $incoming->count() }}</b> permintaan baru</a>
            @endif
            @if($outgoing->isNotEmpty())
                <span class="stat"><b>{{ $outgoing->count() }}</b> terkirim</span>
            @endif
        </div>
    </header>

    @if(session('success'))<div class="success">✓ <span>{{ session('success') }}</span></div>@endif
    @if($errors->any())<div class="errors">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif

    {{-- HASIL PENCARIAN --}}
    @if(mb_strlen($q) >= 2)
    <section class="panel">
        <div class="panel-head">
            <h2>Hasil pencarian</h2>
            <span class="count">{{ $results->count() }}</span>
        </div>
        <div class="list">
            @forelse($results as $u)
                @php $f = $relations[$u->id] ?? null; @endphp
                <div class="person">
                    @if($u->avatar_path)
                        <img class="avatar" src="{{ asset('storage/' . $u->avatar_path) }}" alt="Foto {{ $u->name }}">
                    @else
                        <div class="avatar {{ $tone($u) }}">{{ $initial($u) }}</div>
                    @endif

                    <div class="who">
                        <strong>{{ $u->name }}</strong>
                        <span class="un">@ {{ $handle($u) }}</span>
                        @if($u->domicile)<div class="dom">📍 {{ $u->domicile }}</div>@endif
                    </div>

                    <div class="acts">
                        @if(!$f)
                            <form method="POST" action="{{ route('friends.store', $u) }}">@csrf
                                <button class="small-btn red">＋ Tambah teman</button>
                            </form>
                        @elseif($f->isAccepted())
                            <span class="state">✓ Berteman</span>
                        @elseif($f->requester_id === auth()->id())
                            <span class="state wait">Menunggu respons</span>
                            <form method="POST" action="{{ route('friends.destroy', $f) }}">@csrf @method('DELETE')
                                <button class="small-btn">Batalkan</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('friends.accept', $f) }}">@csrf @method('PATCH')
                                <button class="small-btn green">Terima</button>
                            </form>
                            <form method="POST" action="{{ route('friends.destroy', $f) }}">@csrf @method('DELETE')
                                <button class="small-btn ghost-red">Tolak</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty">
                    <div class="ico">🔍</div>
                    <strong>Pengguna tidak ditemukan</strong>
                    Tidak ada pengguna dengan username atau nama “{{ $q }}”.<br>Periksa ejaannya atau coba kata kunci lain.
                </div>
            @endforelse
        </div>
    </section>
    @endif

    {{-- PERMINTAAN MASUK --}}
    @if($incoming->isNotEmpty())
    <section class="panel incoming" id="permintaan">
        <div class="panel-head">
            <h2>Permintaan pertemanan</h2>
            <span class="count">{{ $incoming->count() }}</span>
        </div>
        <div class="list">
            @foreach($incoming as $f)
                @php $u = $f->requester; @endphp
                <div class="person">
                    @if($u->avatar_path)<img class="avatar" src="{{ asset('storage/' . $u->avatar_path) }}" alt="Foto {{ $u->name }}">@else<div class="avatar {{ $tone($u) }}">{{ $initial($u) }}</div>@endif
                    <div class="who">
                        <strong>{{ $u->name }}</strong>
                        <span class="un">@ {{ $handle($u) }}</span>
                        @if($u->domicile)<div class="dom">📍 {{ $u->domicile }}</div>@endif
                    </div>
                    <div class="acts">
                        <form method="POST" action="{{ route('friends.accept', $f) }}">@csrf @method('PATCH')<button class="small-btn green">Terima</button></form>
                        <form method="POST" action="{{ route('friends.destroy', $f) }}">@csrf @method('DELETE')<button class="small-btn ghost-red">Tolak</button></form>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- DAFTAR TEMAN --}}
    <section class="panel">
        <div class="panel-head">
            <h2>Teman saya</h2>
            <span class="count">{{ $friends->count() }}</span>
        </div>
        @if($friends->isEmpty())
            <div class="empty">
                <div class="ico">🧭</div>
                <strong>Belum ada teman perjalanan</strong>
                Cari username temanmu di kolom pencarian di atas.
            </div>
        @else
            <div class="list">
                @foreach($friends as $f)
                    @php $u = $f->requester_id === auth()->id() ? $f->addressee : $f->requester; @endphp
                    <div class="person">
                        @if($u->avatar_path)<img class="avatar" src="{{ asset('storage/' . $u->avatar_path) }}" alt="Foto {{ $u->name }}">@else<div class="avatar {{ $tone($u) }}">{{ $initial($u) }}</div>@endif
                        <div class="who">
                            <strong>{{ $u->name }}</strong>
                            <span class="un">@ {{ $handle($u) }}</span>
                            @if($u->domicile)<div class="dom">📍 {{ $u->domicile }}</div>@endif
                        </div>
                        <div class="acts">
                            <form method="POST" action="{{ route('friends.destroy', $f) }}" onsubmit="return confirm('Hapus {{ e($u->name) }} dari daftar teman?')">@csrf @method('DELETE')
                                <button class="small-btn ghost-red">Hapus teman</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- PERMINTAAN TERKIRIM --}}
    @if($outgoing->isNotEmpty())
    <section class="panel">
        <div class="panel-head">
            <h2>Permintaan terkirim</h2>
            <span class="count">{{ $outgoing->count() }}</span>
        </div>
        <div class="list">
            @foreach($outgoing as $f)
                @php $u = $f->addressee; @endphp
                <div class="person">
                    @if($u->avatar_path)<img class="avatar" src="{{ asset('storage/' . $u->avatar_path) }}" alt="Foto {{ $u->name }}">@else<div class="avatar {{ $tone($u) }}">{{ $initial($u) }}</div>@endif
                    <div class="who">
                        <strong>{{ $u->name }}</strong>
                        <span class="un">@ {{ $handle($u) }}</span>
                    </div>
                    <div class="acts">
                        <span class="state wait">Menunggu respons</span>
                        <form method="POST" action="{{ route('friends.destroy', $f) }}">@csrf @method('DELETE')<button class="small-btn">Batalkan</button></form>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif
</main>
</body>
</html>