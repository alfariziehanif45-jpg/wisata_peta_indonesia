@php
    $allRoles = $allRoles ?? \App\Models\CustomRole::orderBy('name')->get();
    $owned    = $user->customRoles->pluck('id')->all();
@endphp

<div class="space-y-3">
    {{-- 1. UBAH ROLE SISTEM (Owner / Admin / Pengguna) --}}
    <form method="POST" action="{{ route('admin.users.system-role.update', $user) }}"
          class="rounded-lg bg-gray-50 p-2.5 border border-gray-200 space-y-1.5">
        @csrf @method('PUT')
        <div class="flex items-center justify-between">
            <label class="text-[11px] font-bold text-gray-700">Role Sistem</label>
            <span class="text-[10px] text-gray-500 font-medium">Beri / cabut akses</span>
        </div>

        <div class="flex items-center gap-1.5">
            <select name="role" class="w-full rounded-lg border border-gray-300 bg-white px-2 py-1.5 text-xs font-medium text-gray-800">
                <option value="user" @selected($user->role === 'user')>👤 Pengguna Biasa</option>
                <option value="admin" @selected($user->role === 'admin')>🛡️ Admin (Hanya Pantau)</option>
                <option value="owner" @selected($user->role === 'owner')>👑 Owner (Akses Penuh)</option>
            </select>
            <button class="shrink-0 rounded-lg bg-gray-900 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-black transition"
                    onclick="return confirm('Ubah role sistem untuk {{ $user->name }}?')">
                Simpan
            </button>
        </div>

        <p class="text-[10px] text-gray-500 leading-tight">
            @if ($user->isAdmin())
                Pilih "Pengguna Biasa" untuk mencabut role admin.
            @elseif ($user->isOwner())
                Owner memiliki wewenang penuh mengatur role semua orang.
            @else
                Pilih "Admin" untuk memberikan hak pantau admin.
            @endif
        </p>
    </form>

    {{-- 2. CENTANG / CABUT ROLE KUSTOM --}}
    <div class="rounded-lg border border-gray-200 p-2.5">
        <p class="text-[11px] font-bold text-gray-700 mb-2">Role Kustom (Label/Badge)</p>

        @if ($allRoles->isEmpty())
            <p class="text-xs text-gray-400 mb-2">Belum ada role kustom.</p>
        @else
            <form method="POST" action="{{ route('admin.users.roles.update', $user) }}" class="space-y-2 mb-2">
                @csrf @method('PUT')
                <div class="flex flex-wrap gap-1.5 max-h-36 overflow-y-auto pr-1">
                    @foreach ($allRoles as $role)
                        <label class="inline-flex cursor-pointer items-center gap-1 rounded-full border px-2 py-0.5 text-[11px] hover:bg-gray-50">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" @checked(in_array($role->id, $owned))>
                            <span class="font-semibold" style="color: {{ $role->color }}">{{ $role->icon }} {{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
                <button class="w-full rounded-lg bg-gray-900 py-1 text-xs font-semibold text-white hover:bg-black transition">
                    Simpan Role Kustom
                </button>
            </form>
        @endif

        {{-- 3. BUAT ROLE BARU + LANGSUNG DIBERIKAN --}}
        <form method="POST" action="{{ route('admin.users.roles.create', $user) }}"
              class="space-y-1.5 border-t border-dashed pt-2 mt-2">
            @csrf
            <p class="text-[11px] font-bold text-gray-600">+ Buat role kustom baru</p>
            <input name="name" maxlength="40" required placeholder="Nama role (mis. Fotografer)"
                   class="w-full rounded-lg border px-2 py-1 text-xs">
            <div class="flex gap-1.5">
                <input name="icon" maxlength="16" placeholder="Ikon 📸"
                       class="w-16 rounded-lg border px-2 py-1 text-xs">
                <input type="color" name="color" value="#e11d48"
                       class="h-[28px] w-10 rounded-lg border p-0.5">
                <input name="description" maxlength="160" placeholder="Deskripsi singkat"
                       class="min-w-0 flex-1 rounded-lg border px-2 py-1 text-xs">
            </div>
            <button class="w-full rounded-lg bg-red-600 py-1 text-xs font-semibold text-white hover:bg-red-700 transition">
                Buat &amp; Berikan
            </button>
        </form>
    </div>
</div>
