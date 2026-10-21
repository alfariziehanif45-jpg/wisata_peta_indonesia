<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $profile['name'] }} - Profil Teman</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{
    --red:#dc2626;--red-dark:#b91c1c;--red-deep:#7f1d1d;--red-soft:#fef2f2;--red-line:#fecaca;
    --gold:#f59e0b;--green:#16a34a;--green-soft:#f0fdf4;
    --ink:#1c1917;--muted:#78716c;--line:#ece8e4;--bg:#faf8f6;--card:#fff;
    --shadow:0 1px 2px rgba(28,25,23,.04),0 10px 30px rgba(28,25,23,.07);
}
*{box-sizing:border-box}
body{margin:0;min-height:100vh;background:var(--bg);color:var(--ink);font-family:"Plus Jakarta Sans","Segoe UI",system-ui,-apple-system,Arial,sans-serif;-webkit-font-smoothing:antialiased;line-height:1.5;transition:background .5s}
.container{max-width:780px;margin:auto;padding:16px 16px 64px}

/* ===== HERO + SAMPUL ===== */
.hero{
    position:relative;overflow:hidden;border-radius:28px;color:#fff;margin-bottom:22px;
    background:
        radial-gradient(circle at 1px 1px,rgba(255,255,255,.13) 1.4px,transparent 1.6px) 0 0/16px 16px,
        linear-gradient(135deg,var(--red-deep) 0%,var(--red-dark) 45%,var(--red) 100%);
    box-shadow:0 18px 40px rgba(127,29,29,.28);
}

/* Banner sampul */
.hero-cover{
    position:relative;height:190px;width:100%;
    background-size:cover;background-position:center;background-repeat:no-repeat;
}
/* gradasi gelap tipis agar tombol kembali & transisi ke bawah tetap rapi */
.hero-cover::after{
    content:"";position:absolute;inset:0;pointer-events:none;
    background:linear-gradient(180deg,rgba(0,0,0,.28) 0%,rgba(0,0,0,0) 45%,rgba(127,29,29,.55) 100%);
}
/* dekorasi diamond hanya saat belum ada sampul */
.hero-cover.empty::before{
    content:"";position:absolute;width:150px;height:150px;right:30px;top:-50px;
    border:2px solid rgba(245,158,11,.45);transform:rotate(45deg);border-radius:18px;
}
.cover-label{
    position:absolute;right:14px;bottom:12px;z-index:2;font-size:12px;font-weight:700;
    padding:4px 10px;border-radius:99px;background:rgba(0,0,0,.35);color:#fff;
    backdrop-filter:blur(4px);
}
.hero-cover:not(.empty) ~ .x{display:none}

.back{
    position:absolute;left:16px;top:16px;z-index:3;
    display:inline-flex;align-items:center;gap:6px;color:#fff;text-decoration:none;font-weight:600;font-size:14px;
    padding:6px 12px 6px 8px;border-radius:99px;background:rgba(0,0,0,.35);backdrop-filter:blur(4px);transition:background .15s
}
.back:hover{background:rgba(0,0,0,.55)}
.back svg{width:16px;height:16px}
.back:focus-visible{outline:3px solid var(--gold);outline-offset:2px}

.hero-body{position:relative;padding:0 28px 28px}
.head{display:flex;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-top:-48px}
.avatar{flex:none;width:96px;height:96px;border-radius:50%;object-fit:cover;display:grid;place-items:center;font-weight:800;font-size:40px;border:4px solid #fff;box-shadow:0 8px 20px rgba(0,0,0,.25)}
.t0{background:linear-gradient(135deg,#fee2e2,#fecaca);color:#991b1b}
.t1{background:linear-gradient(135deg,#ffedd5,#fed7aa);color:#9a3412}
.t2{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#92400e}
.t3{background:linear-gradient(135deg,#dcfce7,#bbf7d0);color:#166534}
.t4{background:linear-gradient(135deg,#e0f2fe,#bae6fd);color:#075985}
.t5{background:linear-gradient(135deg,#fae8ff,#f5d0fe);color:#86198f}
.head h1{font-size:clamp(26px,5vw,36px);line-height:1.1;margin:0 0 4px;letter-spacing:-.03em;font-weight:800;overflow-wrap:anywhere;color:#fff}
.head .un{font-weight:600;color:rgba(255,255,255,.9);overflow-wrap:anywhere}
.badge{display:inline-flex;align-items:center;gap:6px;margin-top:12px;font-size:13px;font-weight:700;padding:6px 14px;border-radius:99px;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.25);color:#fff}

/* ===== PANEL ===== */
.panel{background:var(--card);border-radius:24px;box-shadow:var(--shadow);padding:22px 22px 24px;margin-bottom:20px}
.panel-head{display:flex;align-items:center;gap:10px;margin:0 0 16px}
.panel-head h2{font-size:18px;margin:0;letter-spacing:-.01em;font-weight:800}
.readonly{font-size:12px;font-weight:700;color:var(--muted);background:#f5f3f1;padding:3px 10px;border-radius:99px}
.live{display:inline-flex;align-items:center;gap:6px;margin-left:auto;font-size:12px;font-weight:700;color:var(--green);background:var(--green-soft);padding:3px 10px;border-radius:99px}
.live i{width:7px;height:7px;border-radius:50%;background:var(--green);animation:pulse 1.8s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.25}}

.fields{display:grid;gap:12px;margin:0}
.field{padding:13px 16px;border:1px solid var(--line);border-radius:16px;background:#fcfbfa;transition:background .6s,border-color .6s}
.field.flash{background:#fffbeb;border-color:#fde68a}
.field dt{font-size:13px;font-weight:600;color:var(--muted);margin:0 0 2px}
.field dd{margin:0;font-size:15px;font-weight:600;overflow-wrap:anywhere;white-space:pre-line}
.field dd.none{color:#a8a29e;font-weight:500;font-style:italic}

.toast{position:fixed;left:50%;bottom:24px;transform:translate(-50%,20px);background:var(--ink);color:#fff;padding:10px 18px;border-radius:99px;font-weight:600;font-size:14px;opacity:0;pointer-events:none;transition:.3s;z-index:50;max-width:90vw;text-align:center}
.toast.show{opacity:1;transform:translate(-50%,0)}

@media(max-width:560px){
    .container{padding:10px 10px 44px}
    .hero{border-radius:22px}
    .hero-cover{height:140px}
    .hero-body{padding:0 18px 22px}
    .head{margin-top:-40px}
    .panel{padding:18px 14px 20px;border-radius:20px}
    .avatar{width:80px;height:80px;font-size:32px}
}
@media(prefers-reduced-motion:reduce){
    .live i{animation:none}
    .toast,.field,body{transition:none}
}
</style>
</head>
<body @if($profile['bg']) style="background:{{ $profile['bg'] }};background-size:cover;background-position:center;background-attachment:fixed" @endif>
@php
    $handle = $profile['username'];
    $initial = mb_strtoupper(mb_substr($profile['name'] ?: '?', 0, 1));
@endphp
<main class="container">

    <header class="hero">
        {{-- Sampul profil teman --}}
        <div id="cover" class="hero-cover {{ $profile['cover'] ? '' : 'empty' }}"
             @if($profile['cover']) style="background-image:{{ $profile['cover'] }}" @endif>

            <a class="back" href="{{ route('friends.index') }}">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4l-6 6 6 6"/></svg>
                Kembali ke teman
            </a>
        </div>

        <div class="hero-body">
            <div class="head">
                <div id="avatarWrap">
                    @if($profile['avatar'])
                        <img class="avatar" src="{{ $profile['avatar'] }}" alt="Foto {{ $profile['name'] }}">
                    @else
                        <div class="avatar {{ $profile['tone'] }}">{{ $initial }}</div>
                    @endif
                </div>
                <div>
                    <h1 id="pName" @if($profile['name_color']) style="color:{{ $profile['name_color'] }}" @endif>{{ $profile['name'] }}</h1>
                    <div class="un" id="pUserWrap" @if($profile['username_color']) style="color:{{ $profile['username_color'] }}" @endif>@ <span id="pUser">{{ $handle }}</span></div>
                    <span class="badge">✓ Berteman sejak {{ optional($friendship->created_at)->translatedFormat('d F Y') ?? '-' }}</span>
                </div>
            </div>
        </div>
    </header>

    <section class="panel">
        <div class="panel-head">
            <h2>Informasi profil</h2>
            <span class="readonly">Hanya lihat</span>
            <span class="live" title="Perubahan profil teman tampil otomatis"><i></i>Otomatis</span>
        </div>

        <dl class="fields">
            <div class="field" id="boxName">
                <dt>Nama</dt>
                <dd id="fName">{{ $profile['name'] }}</dd>
            </div>
            <div class="field" id="boxUser">
                <dt>Username</dt>
                <dd>@ <span id="fUser">{{ $handle }}</span></dd>
            </div>
            <div class="field" id="boxDom">
                <dt>Domisili</dt>
                <dd id="fDom" class="{{ $profile['domicile'] ? '' : 'none' }}">{{ $profile['domicile'] ? '📍 ' . $profile['domicile'] : 'Belum diisi' }}</dd>
            </div>
            <div class="field" id="boxBio">
                <dt>Tentang</dt>
                <dd id="fBio" class="{{ !empty($profile['bio']) ? '' : 'none' }}">{{ !empty($profile['bio']) ? $profile['bio'] : 'Belum diisi' }}</dd>
            </div>
            <div class="field">
                <dt>Bergabung</dt>
                <dd>{{ optional($user->created_at)->translatedFormat('F Y') ?? '-' }}</dd>
            </div>
        </dl>
    </section>
</main>

<div id="toast" class="toast" role="status" aria-live="polite"></div>

<script>
(() => {
    const URL_DATA = @json(route('friends.data', $user));
    const BACK     = @json(route('friends.index'));
    const INTERVAL = 8000; // ms (cek ke server tiap 8 detik)

    let last  = @json($profile);   // keadaan terakhir yang tampil
    let timer = null;
    let busy  = false;

    const $ = id => document.getElementById(id);

    function toast(msg) {
        const t = $('toast');
        t.textContent = msg;
        t.classList.add('show');
        clearTimeout(toast._t);
        toast._t = setTimeout(() => t.classList.remove('show'), 3000);
    }

    function flash(boxId) {
        const el = $(boxId);
        if (!el) return;
        el.classList.add('flash');
        setTimeout(() => el.classList.remove('flash'), 2500);
    }

    function setField(el, text, prefix = '') {
        if (text) { el.textContent = prefix + text; el.classList.remove('none'); }
        else      { el.textContent = 'Belum diisi'; el.classList.add('none'); }
    }

    // Ambil URL gambar dari nilai CSS url("...") (untuk preload agar tidak berkedip)
    function imageUrl(css) {
        const m = css && css.match(/^url\(["']?(.*?)["']?\)/);
        return m ? m[1] : null;
    }

    // Terapkan latar (gambar / warna / gradient) ke sebuah elemen
    function paint(el, css, prop) {
        const apply = () => {
            if (css) {
                if (prop === 'image') {
                    el.style.backgroundImage = css;
                } else {
                    el.style.background = css;
                    el.style.backgroundSize = 'cover';
                    el.style.backgroundPosition = 'center';
                }
            } else {
                if (prop === 'image') el.style.backgroundImage = '';
                else el.style.background = '';
            }
        };
        const img = imageUrl(css);
        if (img) {
            const pre = new Image();
            pre.onload = apply;
            pre.onerror = apply;
            pre.src = img;
        } else {
            apply();
        }
    }

    function render(d) {
        const changed = [];

        if (d.name !== last.name) {
            $('pName').textContent = d.name;
            $('fName').textContent = d.name;
            document.title = d.name + ' - Profil Teman';
            flash('boxName'); changed.push('nama');
        }
        if (d.username !== last.username) {
            $('pUser').textContent = d.username;
            $('fUser').textContent = d.username;
            flash('boxUser'); changed.push('username');
        }
        if (d.domicile !== last.domicile) {
            setField($('fDom'), d.domicile, '📍 ');
            flash('boxDom'); changed.push('domisili');
        }
        if (d.bio !== last.bio) {
            setField($('fBio'), d.bio);
            flash('boxBio'); changed.push('tentang');
        }

        // Sampul profil teman
        if (d.cover !== last.cover) {
            const cover = $('cover');
            paint(cover, d.cover, 'image');
            cover.classList.toggle('empty', !d.cover);
            changed.push('sampul');
        }

        if (d.bg !== last.bg) {
            const b = document.body;
            paint(b, d.bg);
            b.style.backgroundAttachment = d.bg ? 'fixed' : '';
            changed.push('latar halaman');
        }

        if (d.name_color !== last.name_color || d.username_color !== last.username_color) {
            $('pName').style.color = d.name_color || '';
            $('pUserWrap').style.color = d.username_color || '';
            changed.push('warna tulisan');
        }

        if (d.avatar !== last.avatar || d.name !== last.name || d.tone !== last.tone) {
            const wrap = $('avatarWrap');
            wrap.textContent = '';
            if (d.avatar) {
                const img = document.createElement('img');
                img.className = 'avatar';
                img.src = d.avatar;
                img.alt = 'Foto ' + d.name;
                wrap.appendChild(img);
            } else {
                const div = document.createElement('div');
                div.className = 'avatar ' + (d.tone || 't0');
                div.textContent = (d.name || '?').charAt(0).toUpperCase();
                wrap.appendChild(div);
            }
            if (d.avatar !== last.avatar) changed.push('foto profil');
        }

        last = d;

        toast(changed.length
            ? 'Teman Anda memperbarui: ' + changed.join(', ')
            : 'Profil teman diperbarui');
    }

    async function check() {
        if (busy) return;
        busy = true;
        try {
            const r = await fetch(URL_DATA, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
                cache: 'no-store'
            });

            // Tidak berteman lagi / sesi habis -> kembali ke daftar teman
            if (r.status === 401 || r.status === 403 || r.status === 404) {
                location.href = BACK;
                return;
            }
            if (!r.ok) return;

            const d = await r.json();
            if (d.version !== last.version) render(d);
        } catch (e) {
            /* abaikan, coba lagi di siklus berikutnya */
        } finally {
            busy = false;
        }
    }

    function start() { if (!timer) timer = setInterval(check, INTERVAL); }
    function stop()  { clearInterval(timer); timer = null; }

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) stop(); else { check(); start(); }
    });
    start();
})();
</script>
</body>
</html>