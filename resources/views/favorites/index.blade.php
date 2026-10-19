
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Favorit Saya - Jelajah Wisata Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 text-gray-800">

<div class="mx-auto max-w-6xl px-4 py-8">

    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Favorit Saya</h1>
            <p class="mt-2 text-gray-500">
                Simpan dan kelola destinasi yang ingin kamu kunjungi.
            </p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('profile.show') }}"
               class="rounded-lg border bg-white px-4 py-2">
                Kembali ke Profil
            </a>

            <a href="{{ route('favorites.create') }}"
               class="rounded-lg bg-red-600 px-4 py-2 font-semibold text-white">
                + Tambah Destinasi
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-100 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-lg bg-red-100 p-4 text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    @if($favorites->count())
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($favorites as $favorite)
                <article class="rounded-xl border bg-white p-5 shadow-sm">
                    <div class="mb-3 flex items-start justify-between gap-3">
                        <h2 class="text-xl font-bold">
                            {{ $favorite->name }}
                        </h2>

                        <span class="rounded-full bg-red-50 px-3 py-1 text-xs text-red-700">
                            {{ $favorite->category ?: 'Lainnya' }}
                        </span>
                    </div>

                    @if($favorite->address)
                        <p class="mb-3 text-sm text-gray-600">
                            📍 {{ $favorite->address }}
                        </p>
                    @endif

                    @if($favorite->description)
                        <p class="mb-4 whitespace-pre-line text-sm text-gray-600">
                            {{ $favorite->description }}
                        </p>
                    @endif

                    <div class="mb-4 flex flex-wrap gap-3 text-sm">
                        @if($favorite->url)
                            <a href="{{ $favorite->url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="font-semibold text-blue-600">
                                Buka Google Maps ↗
                            </a>
                        @endif
                    </div>

                    <div class="flex gap-2 border-t pt-4">
                        <a href="{{ route('favorites.edit', $favorite->id) }}"
                           class="rounded-lg bg-amber-100 px-3 py-2 text-sm font-semibold text-amber-800">
                            Edit
                        </a>

                        <form action="{{ route('favorites.destroy', $favorite->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus destinasi ini?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="rounded-lg bg-red-100 px-3 py-2 text-sm font-semibold text-red-700">
                                Hapus
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $favorites->links() }}
        </div>
    @else
        <div class="rounded-2xl border border-dashed bg-white px-6 py-16 text-center">
            <div class="mb-3 text-5xl">♡</div>
            <h2 class="text-xl font-bold">Belum ada destinasi favorit</h2>
            <p class="mt-2 text-gray-500">
                Tambahkan wisata, kuliner, penginapan, atau destinasi lainnya.
            </p>
            <a href="{{ route('favorites.create') }}"
               class="mt-5 inline-block rounded-lg bg-red-600 px-5 py-3 font-semibold text-white">
                Tambahkan Destinasi Pertama
            </a>
        </div>
    @endif

</div>

</body>
</html>
