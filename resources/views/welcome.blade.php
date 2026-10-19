<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Jelajah Wisata Indonesia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-gray-950 text-white">

    {{-- NAVBAR --}}
    <nav class="w-full bg-gray-950 text-white border-b border-white/10 shadow-lg">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between gap-4">

            <a href="{{ url('/') }}"
               class="flex items-center gap-2 text-lg md:text-xl font-bold">
                <i data-lucide="compass"
                   class="w-7 h-7 text-red-500"></i>
                <span>
                    <span class="text-red-500">Jelajah</span> Wisata Indonesia
                </span>
            </a>

            <div class="flex items-center gap-4 md:gap-6">

                <a href="{{ url('/') }}"
                   class="text-gray-300 hover:text-red-400 transition">
                    Beranda
                </a>

                {{-- Sesuaikan URL ini jika halaman peta memakai route lain --}}
                <a href="{{ route('map.index') }}"
                   class="flex items-center gap-2 text-gray-300 hover:text-red-400 transition">
                    <i data-lucide="map" class="w-5 h-5"></i>
                    <span>Peta Wisata</span>
                </a>

                @auth
                    <a href="{{ route('profile.show') }}"
                       class="flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 hover:bg-red-600 transition"
                       title="Buka profil"
                       aria-label="Buka halaman profil">
                        <i data-lucide="user-round" class="w-5 h-5"></i>
                        <span>Profil</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="text-gray-300 hover:text-red-400 transition">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 hover:bg-red-600 transition">
                        <i data-lucide="user-round" class="w-5 h-5"></i>
                        <span>Masuk</span>
                    </a>

                    <a href="{{ route('register') }}"
                       class="rounded-lg border border-white/20 px-4 py-2 hover:bg-white/10 transition">
                        Daftar
                    </a>
                @endauth

            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section class="relative min-h-[85vh] flex items-center overflow-hidden">

        <div class="absolute inset-0">
            <img
                src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=2000&q=85"
                alt="Pemandangan wisata Indonesia"
                class="w-full h-full object-cover"
            >

            <div class="absolute inset-0 bg-black/65"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto w-full px-6 py-24">
            <div class="max-w-3xl">

                <span class="inline-flex items-center gap-2 rounded-full border border-red-500/40 bg-red-500/10 px-4 py-2 text-sm text-red-300 mb-6">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    Jelajahi keindahan Nusantara
                </span>

                <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold leading-tight mb-6">
                    Temukan Keindahan
                    <span class="text-red-500">Indonesia</span>
                </h1>

                <p class="text-gray-200 text-base md:text-xl leading-relaxed mb-8 max-w-2xl">
                    Dari pantai yang memukau hingga pegunungan yang menawan,
                    temukan destinasi wisata terbaik di seluruh Indonesia
                    dan rencanakan petualanganmu berikutnya.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('map.index') }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-6 py-3 font-semibold hover:bg-red-700 transition duration-300">
                        <i data-lucide="map" class="w-5 h-5"></i>
                        Jelajahi Peta
                    </a>

                    <a href="#tentang"
                       class="inline-flex items-center gap-2 rounded-lg border border-white/30 bg-white/10 px-6 py-3 font-semibold hover:bg-white/20 transition duration-300">
                        <i data-lucide="compass" class="w-5 h-5"></i>
                        Tentang Kami
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- KEUNGGULAN --}}
    <section class="py-20 bg-gray-950">
        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-14">
                <p class="text-red-500 font-semibold uppercase tracking-widest text-sm mb-3">
                    Jelajah Nusantara
                </p>

                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    Petualangan Dimulai di Sini
                </h2>

                <p class="text-gray-400 max-w-2xl mx-auto">
                    Temukan informasi wisata untuk membantumu menjelajahi
                    kekayaan alam dan budaya Indonesia.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="rounded-2xl border border-white/10 bg-white/5 p-8 hover:border-red-500/50 transition duration-300">
                    <div class="w-14 h-14 rounded-xl bg-red-500/10 flex items-center justify-center mb-6">
                        <i data-lucide="map" class="w-7 h-7 text-red-500"></i>
                    </div>

                    <h3 class="text-xl font-bold mb-3">Peta Wisata</h3>

                    <p class="text-gray-400 leading-relaxed mb-5">
                        Jelajahi lokasi wisata melalui peta dan temukan
                        destinasi menarik di berbagai daerah.
                    </p>

                    <a href="{{ route('map.index') }}"
                       class="inline-flex items-center gap-2 text-red-400 hover:text-red-300">
                        Buka peta
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-8 hover:border-red-500/50 transition duration-300">
                    <div class="w-14 h-14 rounded-xl bg-red-500/10 flex items-center justify-center mb-6">
                        <i data-lucide="mountain" class="w-7 h-7 text-red-500"></i>
                    </div>

                    <h3 class="text-xl font-bold mb-3">Destinasi Menarik</h3>

                    <p class="text-gray-400 leading-relaxed mb-5">
                        Kenali beragam tujuan wisata, mulai dari pantai,
                        pegunungan, hingga tempat bersejarah.
                    </p>

                    <a href="{{ route('map.index') }}"
                       class="inline-flex items-center gap-2 text-red-400 hover:text-red-300">
                        Cari destinasi
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-8 hover:border-red-500/50 transition duration-300">
                    <div class="w-14 h-14 rounded-xl bg-red-500/10 flex items-center justify-center mb-6">
                        <i data-lucide="user-round" class="w-7 h-7 text-red-500"></i>
                    </div>

                    <h3 class="text-xl font-bold mb-3">Profil Pengguna</h3>

                    <p class="text-gray-400 leading-relaxed mb-5">
                        Akses halaman profil untuk melihat dan mengelola
                        informasi akunmu.
                    </p>

                    @auth
                        <a href="{{ route('profile.show') }}"
                           class="inline-flex items-center gap-2 text-red-400 hover:text-red-300">
                            Buka profil
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 text-red-400 hover:text-red-300">
                            Masuk ke akun
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </section>

    {{-- TENTANG --}}
    <section id="tentang" class="py-20 border-t border-white/10">
        <div class="max-w-5xl mx-auto px-6 text-center">

            <i data-lucide="compass" class="w-12 h-12 text-red-500 mx-auto mb-6"></i>

            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                Tentang Jelajah Wisata Indonesia
            </h2>

            <p class="text-gray-400 text-lg leading-relaxed">
                Jelajah Wisata Indonesia merupakan platform untuk membantu
                pengguna mengenal berbagai destinasi wisata di Indonesia.
                Temukan tempat baru, jelajahi wilayah Nusantara, dan dapatkan
                inspirasi untuk perjalananmu berikutnya.
            </p>

        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="border-t border-white/10 bg-black py-8">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">

            <a href="{{ url('/') }}" class="flex items-center gap-2 font-bold">
                <i data-lucide="compass" class="w-5 h-5 text-red-500"></i>
                Jelajah Wisata Indonesia
            </a>

            <p class="text-sm text-gray-500 text-center">
                &copy; {{ date('Y') }} Jelajah Wisata Indonesia.
                Semua hak dilindungi.
            </p>

        </div>
    </footer>

    {{-- INISIALISASI IKON --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) {
                window.lucide.createIcons();
            } else {
                console.error('Lucide gagal dimuat. Periksa koneksi internet atau CDN.');
            }
        });
    </script>

</body>
</html>