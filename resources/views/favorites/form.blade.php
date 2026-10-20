<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $favorite ? 'Edit' : 'Tambah' }} Destinasi Favorit - Jelajah Wisata Indonesia</title>

    <style>
        :root { --red:#dc2626; --red-dark:#b91c1c; --ink:#1f2937; --muted:#6b7280; --line:#e5e7eb; --bg:#f6f7fb; }
        * { box-sizing: border-box; }
        body { margin:0; background:var(--bg); color:var(--ink); font-family:"Inter","Segoe UI",system-ui,Arial,sans-serif; }
        .container { max-width:680px; margin:auto; padding:24px 16px 60px; }
        .back { display:inline-block; margin-bottom:16px; padding:8px 14px; background:#fff; border:1px solid var(--line); border-radius:999px; font-size:14px; font-weight:600; color:#4b5563; text-decoration:none; }
        .card { background:#fff; border-radius:22px; padding:26px; box-shadow:0 1px 2px rgba(16,24,40,.05), 0 8px 24px rgba(16,24,40,.06); }
        h1 { margin:0 0 4px; font-size:22px; }
        .desc { margin:0 0 8px; color:var(--muted); font-size:13px; }
        label { display:block; margin:16px 0 7px; font-size:14px; font-weight:600; }
        input, select, textarea { width:100%; padding:12px 14px; border:1.5px solid #dfe2e8; border-radius:12px; font:15px inherit; font-family:inherit; background:#fcfcfd; color:var(--ink); }
        input:focus, select:focus, textarea:focus { outline:none; border-color:var(--red); box-shadow:0 0 0 4px rgba(220,38,38,.12); background:#fff; }
        textarea { min-height:90px; resize:vertical; }
        .row { display:grid; grid-template-columns:1fr 1fr; gap:0 14px; }
        .hint { margin:6px 0 0; font-size:12px; color:var(--muted); }
        .error { margin-bottom:16px; padding:12px 16px; border:1px solid #fecaca; border-radius:12px; background:#fef2f2; color:#991b1b; font-size:14px; }
        .error ul { margin:0; padding-left:20px; }
        .actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:26px; padding-top:20px; border-top:1px solid var(--line); }
        .btn { display:inline-flex; align-items:center; justify-content:center; padding:12px 20px; border:1px solid #dfe2e8; border-radius:12px; background:#fff; color:var(--ink); font-size:14px; font-weight:700; text-decoration:none; cursor:pointer; font-family:inherit; }
        .primary { background:linear-gradient(135deg,var(--red),var(--red-dark)); border-color:transparent; color:#fff; }
        @media (max-width:600px) { .row { grid-template-columns:1fr; } }
    </style>
</head>
<body>
<main class="container">

    <a class="back" href="{{ route('profile.show') }}#destinasi-favorit">← Kembali ke Profil</a>

    <section class="card">
        <h1>{{ $favorite ? '✏️ Edit Destinasi Favorit' : '➕ Tambah Destinasi Favorit' }}</h1>
        <p class="desc">Destinasi ini akan tampil di "Destinasi Favorit Saya" pada profilmu.</p>

        @if($errors->any())
            <div class="error">
                <ul>
                    @foreach($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ $favorite ? route('favorites.update', $favorite->id) : route('favorites.store') }}">
            @csrf
            @if($favorite)
                @method('PUT')
            @endif

            <label for="name">Nama tempat</label>
            <input id="name" name="name" value="{{ old('name', $favorite->name ?? '') }}" required maxlength="255"
                   placeholder="Contoh: Jembatan Ampera">

            <div class="row">
                <div>
                    <label for="category">Kategori</label>
                    <select id="category" name="category" required>
                        <option value="wisata" {{ old('category', $favorite->category ?? 'wisata') === 'wisata' ? 'selected' : '' }}>🏞️ Wisata</option>
                        <option value="kuliner" {{ old('category', $favorite->category ?? '') === 'kuliner' ? 'selected' : '' }}>🍜 Kuliner</option>
                    </select>
                </div>
                <div>
                    <label for="type">Jenis (opsional)</label>
                    <input id="type" name="type" value="{{ old('type', $favorite->type ?? '') }}" maxlength="255"
                           placeholder="Contoh: Landmark, Pempek">
                </div>
            </div>

            <div class="row">
                <div>
                    <label for="city">Kota / Kabupaten</label>
                    <input id="city" name="city" value="{{ old('city', $favorite->city ?? '') }}" maxlength="100"
                           placeholder="Contoh: Palembang">
                </div>
                <div>
                    <label for="province">Provinsi</label>
                    <input id="province" name="province" value="{{ old('province', $favorite->province ?? '') }}" maxlength="100"
                           placeholder="Contoh: Sumatera Selatan">
                </div>
            </div>

            <label for="address">Alamat (opsional)</label>
            <textarea id="address" name="address" maxlength="500"
                      placeholder="Alamat lengkap tempat">{{ old('address', $favorite->address ?? '') }}</textarea>

            <div class="row">
                <div>
                    <label for="latitude">Latitude (opsional)</label>
                    <input id="latitude" name="latitude" inputmode="decimal"
                           value="{{ old('latitude', $favorite->latitude ?? '') }}" placeholder="-2.9909">
                </div>
                <div>
                    <label for="longitude">Longitude (opsional)</label>
                    <input id="longitude" name="longitude" inputmode="decimal"
                           value="{{ old('longitude', $favorite->longitude ?? '') }}" placeholder="104.7566">
                </div>
            </div>
            <p class="hint">Isi koordinat agar tombol "Rute" muncul di profil.</p>

            <label for="google_maps_url">Tautan Google Maps (opsional)</label>
            <input id="google_maps_url" name="google_maps_url" maxlength="1000"
                   value="{{ old('google_maps_url', $favorite->google_maps_url ?? '') }}" placeholder="https://maps.google.com/...">

            <div class="actions">
                <button class="btn primary" type="submit">💾 Simpan</button>
                <a class="btn" href="{{ route('profile.show') }}#destinasi-favorit">Batal</a>
            </div>
        </form>
    </section>

</main>
</body>
</html>