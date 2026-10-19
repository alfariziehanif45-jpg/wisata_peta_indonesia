
<nav class="bg-gray-950 text-white border-b border-white/10 shadow-lg">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <a href="{{ url('/') }}"
           class="flex items-center gap-2 text-lg md:text-xl font-bold">
            <i data-lucide="compass" class="w-7 h-7 text-red-500"></i>
            <span>Jelajah Wisata Indonesia</span>
        </a>

        <div class="flex items-center gap-4 md:gap-6">
            <a href="{{ url('/') }}"
               class="text-gray-300 hover:text-red-400 transition">
                Beranda
            </a>

            <a href="{{ url('/map') }}"
               class="text-gray-300 hover:text-red-400 transition">
                Peta Wisata
            </a>

            @auth
                <a href="{{ route('profile.show') }}"
                   class="flex items-center gap-2 rounded-full
                          border border-white/20 bg-white/10
                          px-3 py-2 hover:bg-red-600 transition"
                   title="Profil Saya">
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
                   class="rounded-lg bg-red-600 px-4 py-2
                          hover:bg-red-700 transition">
                    Masuk
                </a>

                <a href="{{ route('register') }}"
                   class="rounded-lg border border-white/20 px-4 py-2
                          hover:bg-white/10 transition">
                    Daftar
                </a>
            @endauth
        </div>

    </div>
</nav>
