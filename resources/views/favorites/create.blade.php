
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Favorit</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 px-4 py-8 text-gray-800">

<div class="mx-auto max-w-2xl rounded-2xl bg-white p-6 shadow-sm sm:p-8">
    <h1 class="text-2xl font-bold">Tambah Destinasi Favorit</h1>
    <p class="mb-6 mt-2 text-gray-500">
        Masukkan tempat yang ingin kamu simpan.
    </p>

    @if($errors->any())
        <div class="mb-5 rounded-lg bg-red-100 p-4 text-red-700">
            <ul class="list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('favorites.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="mb-1 block font-semibold">Nama destinasi *</label>
            <input id="name" name="name" value="{{ old('name') }}"
                   required maxlength="255"
                   placeholder="Contoh: Danau Kaco"
                   class="w-full rounded-lg border p-3">
        </div>

        <div>
            <label for="category" class="mb-1 block font-semibold">Kategori *</label>
            <select id="category" name="category" required
                    class="w-full rounded-lg border p-3">
                @foreach(['Wisata Alam', 'Kuliner', 'Sejarah dan Budaya', 'Penginapan', 'Hiburan', 'Lainnya'] as $category)
                    <option value="{{ $category }}"
                        @selected(old('category', 'Kuliner') === $category)>
                        {{ $category }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="address" class="mb-1 block font-semibold">Alamat atau lokasi</label>
            <input id="address" name="address" value="{{ old('address') }}"
                   maxlength="1000"
                   placeholder="Kota, provinsi, atau alamat lengkap"
                   class="w-full rounded-lg border p-3">
        </div>

        <div>
            <label for="url" class="mb-1 block font-semibold">Link Google Maps</label>
            <input id="url" type="url" name="url" value="{{ old('url') }}"
                   maxlength="2048"
                   placeholder="https://maps.google.com/..."
                   class="w-full rounded-lg border p-3">
        </div>

        <div>
            <label for="description" class="mb-1 block font-semibold">Deskripsi atau catatan</label>
            <textarea id="description" name="description" rows="4" maxlength="5000"
                      placeholder="Catatan tentang destinasi ini..."
                      class="w-full rounded-lg border p-3">{{ old('description') }}</textarea>
        </div>

        <div class="flex flex-wrap gap-3 pt-2">
            <button type="submit"
                    class="rounded-lg bg-red-600 px-5 py-3 font-semibold text-white">
                Simpan Favorit
            </button>

            <a href="{{ route('favorites.index') }}"
               class="rounded-lg border px-5 py-3">
                Batal
            </a>
        </div>
    </form>
</div>

</body>
</html>
