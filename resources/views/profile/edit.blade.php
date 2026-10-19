<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profil - Jelajah Wisata Indonesia</title>

    <style>
        :root {
            --red: #dc2626;
            --red-dark: #b91c1c;
            --red-soft: #fef2f2;
            --orange: #f97316;
            --ink: #1f2937;
            --muted: #6b7280;
            --line: #e5e7eb;
            --bg: #f6f7fb;
            --card: #fff;
            --shadow: 0 1px 2px rgba(16, 24, 40, .05), 0 8px 24px rgba(16, 24, 40, .06);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: "Inter", "Segoe UI", system-ui, -apple-system, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 780px;
            margin: auto;
            padding: 24px 20px 60px;
        }

        a { color: inherit; text-decoration: none; }

        .back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 18px;
            padding: 8px 14px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 999px;
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            transition: background .15s, transform .15s;
        }

        .back:hover { background: #f9fafb; transform: translateX(-2px); }

        /* ===== HERO ===== */
        .hero {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 26px 28px;
            margin-bottom: 20px;
            border-radius: 24px;
            color: #fff;
            background:
                radial-gradient(circle at 12% 20%, rgba(255,255,255,.25), transparent 40%),
                linear-gradient(120deg, var(--red-dark), var(--red) 55%, var(--orange));
            box-shadow: 0 12px 30px rgba(220, 38, 38, .25);
        }

        .hero .avatar {
            flex: none;
            width: 84px;
            height: 84px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255,255,255,.9);
            background: #fff7ed;
            display: grid;
            place-items: center;
            font-size: 38px;
        }

        .hero h1 { margin: 0 0 4px; font-size: 26px; letter-spacing: -.02em; }
        .hero p { margin: 0; opacity: .9; font-size: 15px; }

        /* ===== ALERT ===== */
        .success {
            padding: 14px 18px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .error {
            padding: 14px 18px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .error ul { margin: 0; padding-left: 20px; }

        /* ===== CARD ===== */
        .card {
            background: var(--card);
            border-radius: 24px;
            box-shadow: var(--shadow);
            padding: 10px 28px 30px;
        }

        .section { padding-top: 24px; }

        .title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            margin: 0 0 4px;
            letter-spacing: -.01em;
        }

        .title .ico {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: var(--red-soft);
            font-size: 17px;
        }

        .section-desc { margin: 0 0 6px 44px; color: var(--muted); font-size: 13px; }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 18px;
        }

        .full { grid-column: 1 / -1; }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin: 18px 0 8px;
        }

        input[type=text], input[type=email], input[type=password], input:not([type]), textarea {
            width: 100%;
            padding: 13px 15px;
            border: 1.5px solid #dfe2e8;
            border-radius: 12px;
            font: 15px inherit;
            font-family: inherit;
            color: var(--ink);
            background: #fcfcfd;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: var(--red);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, .12);
        }

        input.is-invalid, textarea.is-invalid { border-color: #f87171; }

        textarea { min-height: 120px; resize: vertical; line-height: 1.6; }

        .field-error { display: block; margin-top: 6px; color: var(--red-dark); font-size: 12px; }

        .hint { font-size: 12px; color: var(--muted); margin: 8px 0 0; }

        .counter { text-align: right; font-size: 12px; color: var(--muted); margin-top: 6px; }

        .input-prefix { position: relative; }
        .input-prefix span {
            position: absolute;
            left: 15px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-weight: 600;
        }
        .input-prefix input { padding-left: 34px; }

        .pw { position: relative; }
        .pw input { padding-right: 70px; }
        .pw button {
            position: absolute;
            right: 8px; top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: var(--red);
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            padding: 8px;
            font-family: inherit;
        }

        .line { border: 0; border-top: 1px solid var(--line); margin: 30px 0 0; }

        /* ===== AVATAR CROPPER ===== */
        .avatar-box {
            display: flex;
            gap: 26px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 18px;
            padding: 22px;
            border: 2px dashed #e1e4ea;
            border-radius: 20px;
            background: #fafbfc;
        }

        .stage {
            position: relative;
            flex: none;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            overflow: hidden;
            background: #fff7ed;
            box-shadow: 0 0 0 5px #fff, 0 0 0 7px #fecaca, 0 12px 28px rgba(0,0,0,.12);
            display: grid;
            place-items: center;
            font-size: 80px;
        }

        .stage img, .stage canvas {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .stage canvas {
            cursor: grab;
            touch-action: none;
            outline: none;
        }

        .stage canvas.dragging { cursor: grabbing; }

        .stage.editing::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 50%;
            pointer-events: none;
            background:
                linear-gradient(rgba(255,255,255,.35), rgba(255,255,255,.35)) center/1px 100% no-repeat,
                linear-gradient(rgba(255,255,255,.35), rgba(255,255,255,.35)) center/100% 1px no-repeat;
            box-shadow: inset 0 0 0 2px rgba(255,255,255,.55);
        }

        .avatar-tools { flex: 1; min-width: 220px; }

        .avatar-tools strong { display: block; font-size: 15px; margin-bottom: 4px; }

        .avatar-tools .sub { color: var(--muted); font-size: 13px; line-height: 1.6; margin: 0 0 14px; }

        .sr-only {
            position: absolute;
            width: 1px; height: 1px;
            overflow: hidden;
            clip: rect(0 0 0 0);
            white-space: nowrap;
        }

        .crop-controls { display: none; margin-top: 16px; }
        .crop-controls.show { display: block; }

        .zoom-row { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
        .zoom-row span { font-size: 18px; color: var(--muted); user-select: none; }

        input[type=range] {
            flex: 1;
            -webkit-appearance: none;
            appearance: none;
            height: 6px;
            border-radius: 999px;
            background: #e5e7eb;
            padding: 0;
            border: 0;
            box-shadow: none;
        }

        input[type=range]:focus { box-shadow: none; background: #e5e7eb; }

        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 22px; height: 22px;
            border-radius: 50%;
            background: var(--red);
            border: 3px solid #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,.25);
            cursor: pointer;
        }

        input[type=range]::-moz-range-thumb {
            width: 18px; height: 18px;
            border-radius: 50%;
            background: var(--red);
            border: 3px solid #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,.25);
            cursor: pointer;
        }

        .tip {
            font-size: 12px;
            color: #92400e;
            background: #fffbeb;
            border: 1px solid #fde68a;
            padding: 8px 12px;
            border-radius: 10px;
            margin-bottom: 12px;
        }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 12px 20px;
            border: 1px solid #dfe2e8;
            border-radius: 12px;
            background: #fff;
            color: var(--ink);
            font-size: 14px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s, background .15s;
        }

        .btn:hover { background: #f9fafb; transform: translateY(-1px); }

        .btn.sm { padding: 9px 14px; font-size: 13px; }

        .primary {
            background: linear-gradient(135deg, var(--red), var(--red-dark));
            border-color: transparent;
            color: #fff;
            box-shadow: 0 6px 16px rgba(220, 38, 38, .3);
        }

        .primary:hover {
            background: linear-gradient(135deg, #ef4444, var(--red));
            box-shadow: 0 8px 20px rgba(220, 38, 38, .38);
        }

        .primary:disabled { opacity: .7; cursor: wait; transform: none; }

        .btn-row { display: flex; gap: 8px; flex-wrap: wrap; }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 30px;
            padding-top: 24px;
            border-top: 1px solid var(--line);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 600px) {
            .container { padding: 14px 12px 40px; }
            .hero { padding: 20px; gap: 14px; border-radius: 20px; }
            .hero .avatar { width: 66px; height: 66px; font-size: 30px; }
            .hero h1 { font-size: 21px; }
            .card { padding: 6px 18px 24px; border-radius: 20px; }
            .grid { grid-template-columns: 1fr; }
            .avatar-box { justify-content: center; padding: 18px; }
            .section-desc { margin-left: 0; }
            .actions .btn { flex: 1; }
        }
    </style>
</head>

<body>
<main class="container">

    <a class="back" href="{{ route('profile.show') }}">← Kembali ke Profil</a>

    <header class="hero">
        @if($user->avatar_path)
            <img class="avatar" src="{{ asset('storage/'.$user->avatar_path) }}" alt="Foto profil">
        @else
            <div class="avatar">🧳</div>
        @endif

        <div>
            <h1>Edit Profil</h1>
            <p>Perbarui informasi akun penjelajahmu.</p>
        </div>
    </header>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="card">
        <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- FOTO PROFIL --}}
            <div class="section">
                <h2 class="title"><span class="ico">📷</span> Foto profil</h2>
                <p class="section-desc">Pilih foto, lalu geser dan zoom sampai pas di dalam lingkaran.</p>

                <div class="avatar-box">
                    <div class="stage" id="stage">
                        @if($user->avatar_path)
                            <img id="currentAvatar" src="{{ asset('storage/'.$user->avatar_path) }}" alt="Foto profil saat ini">
                        @else
                            <span id="currentAvatar">🧳</span>
                        @endif
                        <canvas id="cropCanvas" tabindex="0" hidden aria-label="Area atur foto profil"></canvas>
                    </div>

                    <div class="avatar-tools">
                        <strong id="avatarTitle">Foto saat ini</strong>
                        <p class="sub" id="avatarSub">Format JPG, PNG, atau WEBP, maksimal 2 MB.</p>

                        <input class="sr-only" id="avatar" type="file" name="avatar"
                               accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                        <div class="btn-row">
                            <label class="btn sm" for="avatar" style="margin:0">📁 Pilih foto</label>
                        </div>

                        <div class="crop-controls" id="cropControls">
                            <div class="tip">☝️ Seret foto ke arah mana pun untuk menggeser. Geser slider (atau scroll mouse) untuk memperbesar atau memperkecil sampai pas.</div>

                            <div class="zoom-row">
                                <span>－</span>
                                <input type="range" id="zoom" min="1" max="3" step="0.01" value="1" aria-label="Zoom">
                                <span>＋</span>
                            </div>

                            <div class="btn-row" style="margin-bottom:8px">
                                <button type="button" class="btn sm" id="fitCrop">⤢ Pas ukuran</button>
                                <button type="button" class="btn sm" id="fillCrop">⬤ Penuhi lingkaran</button>
                            </div>

                            <div class="btn-row">
                                <button type="button" class="btn sm" id="resetCrop">↺ Atur ulang</button>
                                <button type="button" class="btn sm" id="cancelCrop">✕ Batalkan foto</button>
                            </div>
                        </div>
                    </div>
                </div>
                @error('avatar')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <hr class="line">

            {{-- INFORMASI PRIBADI --}}
            <div class="section">
                <h2 class="title"><span class="ico">♙</span> Informasi pribadi</h2>
                <p class="section-desc">Data ini tampil di halaman profilmu.</p>

                <div class="grid">
                    <div>
                        <label for="name">Nama penjelajah</label>
                        <input id="name" name="name" class="@error('name') is-invalid @enderror"
                               value="{{ old('name',$user->name) }}" required maxlength="255">
                        @error('name')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="username">Username</label>
                        <div class="input-prefix">
                            <span>@</span>
                            <input id="username" name="username" class="@error('username') is-invalid @enderror"
                                   value="{{ old('username',$user->username ?: \Illuminate\Support\Str::slug($user->name,'_')) }}"
                                   required maxlength="30">
                        </div>
                        @error('username')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="email">Alamat email</label>
                        <input id="email" type="email" name="email" class="@error('email') is-invalid @enderror"
                               value="{{ old('email',$user->email) }}" required>
                        @error('email')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="domicile">Domisili</label>
                        <input id="domicile" name="domicile" class="@error('domicile') is-invalid @enderror"
                               value="{{ old('domicile',$user->domicile) }}"
                               placeholder="Contoh: Palembang, Sumatera Selatan" maxlength="100">
                        @error('domicile')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="full">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" maxlength="500"
                                  class="@error('bio') is-invalid @enderror"
                                  placeholder="Ceritakan sedikit tentang dirimu">{{ old('bio',$user->bio) }}</textarea>
                        <div class="counter"><span id="bioCount">0</span>/500</div>
                        @error('bio')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <hr class="line">

            {{-- KEAMANAN --}}
            <div class="section">
                <h2 class="title"><span class="ico">🔒</span> Keamanan akun</h2>
                <p class="section-desc">Kosongkan jika tidak ingin mengganti password.</p>

                <div class="grid">
                    <div>
                        <label for="password">Password baru</label>
                        <div class="pw">
                            <input id="password" type="password" name="password" minlength="8"
                                   class="@error('password') is-invalid @enderror"
                                   autocomplete="new-password" placeholder="Minimal 8 karakter">
                            <button type="button" data-toggle="password">Lihat</button>
                        </div>
                        @error('password')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="password_confirmation">Konfirmasi password baru</label>
                        <div class="pw">
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                   minlength="8" autocomplete="new-password" placeholder="Ulangi password baru">
                            <button type="button" data-toggle="password_confirmation">Lihat</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="actions">
                <button class="btn primary" id="saveBtn" type="submit">💾 Simpan Perubahan</button>
                <a class="btn" href="{{ route('profile.show') }}">Batal</a>
            </div>
        </form>
    </section>

</main>

<script>
(function () {
    /* ===== Counter bio ===== */
    var bio = document.getElementById('bio');
    var bioCount = document.getElementById('bioCount');
    function updateCount() { bioCount.textContent = bio.value.length; }
    bio.addEventListener('input', updateCount);
    updateCount();

    /* ===== Tombol lihat password ===== */
    document.querySelectorAll('[data-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.dataset.toggle);
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.textContent = show ? 'Sembunyikan' : 'Lihat';
        });
    });

    /* ===== Cropper foto profil (lingkaran) ===== */
    var S = 260;          // ukuran kerja area crop (px)
    var OUT = 512;        // ukuran hasil akhir (px)
    var MAX_MB = 2;

    var form = document.getElementById('profileForm');
    var input = document.getElementById('avatar');
    var stage = document.getElementById('stage');
    var canvas = document.getElementById('cropCanvas');
    var current = document.getElementById('currentAvatar');
    var controls = document.getElementById('cropControls');
    var zoomEl = document.getElementById('zoom');
    var title = document.getElementById('avatarTitle');
    var sub = document.getElementById('avatarSub');
    var saveBtn = document.getElementById('saveBtn');
    var ctx = canvas.getContext('2d');

    var dpr = Math.min(window.devicePixelRatio || 1, 2);
    canvas.width = S * dpr;
    canvas.height = S * dpr;

    var img = null, base = 1, zoom = 1, minZoom = 1, ox = 0, oy = 0;
    var defaultSub = sub.textContent;

    function scale() { return base * zoom; }

    
function clamp() {
    if (!img) return;

    const currentScale = scale();
    const w = img.naturalWidth * currentScale;
    const h = img.naturalHeight * currentScale;

    // Jika foto lebih besar daripada frame,
    // foto tidak boleh bergeser sampai muncul ruang kosong.
    const minX = w > S ? (S - w) / 2 : (w - S) / 2;
    const maxX = w > S ? (w - S) / 2 : (S - w) / 2;

    const minY = h > S ? (S - h) / 2 : (h - S) / 2;
    const maxY = h > S ? (h - S) / 2 : (S - h) / 2;

    ox = Math.max(minX, Math.min(maxX, ox));
    oy = Math.max(minY, Math.min(maxY, oy));
}

function draw(c, size) {
    if (!img) return;

    const factor = size / S;
    const currentScale = scale();

    const w = img.naturalWidth * currentScale * factor;
    const h = img.naturalHeight * currentScale * factor;

    c.clearRect(0, 0, size, size);
    c.fillStyle = '#ffffff';
    c.fillRect(0, 0, size, size);

    c.drawImage(
        img,
        size / 2 + ox * factor - w / 2,
        size / 2 + oy * factor - h / 2,
        w,
        h
    );
}


    function render() {
        if (!img) return;
        clamp();
        draw(ctx, canvas.width);
    }

    
function startCrop(image, fileName) {
    img = image;

    // Skala awal agar foto memenuhi frame
    base = Math.max(
        S / img.naturalWidth,
        S / img.naturalHeight
    );

    // Batas zoom minimum untuk melihat foto secara utuh
    minZoom = Math.min(
        S / img.naturalWidth,
        S / img.naturalHeight
    ) / base;

    zoomEl.min = minZoom;
    zoomEl.max = 3;
    zoom = 1;

    ox = 0;
    oy = 0;
    zoomEl.value = zoom;

    current.hidden = true;
    canvas.hidden = false;

    stage.classList.add('editing');
    controls.classList.add('show');

    title.textContent = 'Atur posisi foto';
    sub.textContent = fileName;

    render();
}


    function stopCrop() {
        img = null;
        input.value = '';
        canvas.hidden = true;
        current.hidden = false;
        stage.classList.remove('editing');
        controls.classList.remove('show');
        title.textContent = 'Foto saat ini';
        sub.textContent = defaultSub;
    }

    input.addEventListener('change', function () {
        var file = input.files && input.files[0];
        if (!file) return;

        if (!/^image\/(jpeg|png|webp)$/.test(file.type)) {
            alert('Format foto harus JPG, PNG, atau WEBP.');
            stopCrop();
            return;
        }

        if (file.size > MAX_MB * 1024 * 1024) {
            alert('Ukuran foto maksimal ' + MAX_MB + ' MB.');
            stopCrop();
            return;
        }

        var url = URL.createObjectURL(file);
        var image = new Image();
        image.onload = function () {
            URL.revokeObjectURL(url);
            startCrop(image, file.name);
        };
        image.onerror = function () {
            URL.revokeObjectURL(url);
            alert('Foto tidak bisa dibuka. Coba foto lain.');
            stopCrop();
        };
        image.src = url;
    });

    /* Geser dengan mouse / sentuh */
    var dragging = false, lx = 0, ly = 0;
    var cssSize = function () { return canvas.getBoundingClientRect().width || S; };

    canvas.addEventListener('pointerdown', function (e) {
        if (!img) return;
        dragging = true;
        lx = e.clientX; ly = e.clientY;
        canvas.setPointerCapture(e.pointerId);
        canvas.classList.add('dragging');
    });

    canvas.addEventListener('pointermove', function (e) {
        if (!dragging) return;
        var k = S / cssSize();
        ox += (e.clientX - lx) * k;
        oy += (e.clientY - ly) * k;
        lx = e.clientX; ly = e.clientY;
        render();
    });

    function endDrag() { dragging = false; canvas.classList.remove('dragging'); }
    canvas.addEventListener('pointerup', endDrag);
    canvas.addEventListener('pointercancel', endDrag);

    /* Zoom */
   
function setZoom(z) {
    if (!img) return;

    const nextZoom = Math.max(
        minZoom,
        Math.min(3, Number(z) || minZoom)
    );

    zoom = nextZoom;
    zoomEl.value = zoom;

    render();
}

    zoomEl.addEventListener('input', function () { setZoom(parseFloat(zoomEl.value)); });

    canvas.addEventListener('wheel', function (e) {
        if (!img) return;
        e.preventDefault();
        setZoom(zoom + (e.deltaY < 0 ? 0.08 : -0.08));
    }, { passive: false });

    /* Tombol panah */
    canvas.addEventListener('keydown', function (e) {
        if (!img) return;
        var step = 8;
        if (e.key === 'ArrowLeft') ox -= step;
        else if (e.key === 'ArrowRight') ox += step;
        else if (e.key === 'ArrowUp') oy -= step;
        else if (e.key === 'ArrowDown') oy += step;
        else return;
        e.preventDefault();
        render();
    });

    document.getElementById('resetCrop').addEventListener('click', function () {
        zoom = 1; ox = 0; oy = 0; zoomEl.value = 1; render();
    });

    // Pas: seluruh foto terlihat utuh di dalam lingkaran
    document.getElementById('fitCrop').addEventListener('click', function () {
        zoom = minZoom; ox = 0; oy = 0; zoomEl.value = zoom; render();
    });

    // Penuh: foto memenuhi lingkaran tanpa ruang kosong
    document.getElementById('fillCrop').addEventListener('click', function () {
        zoom = 1; ox = 0; oy = 0; zoomEl.value = zoom; render();
    });

    document.getElementById('cancelCrop').addEventListener('click', stopCrop);

    /* Saat simpan: kirim hasil potongan (persegi 512px, tampil bulat di profil) */
    form.addEventListener('submit', function (e) {
        if (!img) return;
        e.preventDefault();
        saveBtn.disabled = true;
        saveBtn.textContent = 'Menyimpan...';

        var out = document.createElement('canvas');
        out.width = OUT; out.height = OUT;
        draw(out.getContext('2d'), OUT);

        out.toBlob(function (blob) {
            if (!blob) {
                saveBtn.disabled = false;
                saveBtn.textContent = '💾 Simpan Perubahan';
                alert('Gagal memproses foto. Coba lagi.');
                return;
            }
            try {
                var file = new File([blob], 'avatar.jpg', { type: 'image/jpeg' });
                var dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
            } catch (err) { /* browser lama: kirim foto asli */ }
            form.submit();
        }, 'image/jpeg', 0.92);
    });
})();
</script>
</body>
</html>