@extends('admin.layout')

@section('title', 'Role Pengguna')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold">Role Pengguna</h1>
    <p class="mt-1 text-gray-500">
        @if (auth()->user()->isOwner())
            Kelola role kustom (badge) yang dapat diberikan ke pengguna di halaman Daftar Pengguna.
        @else
            Daftar role kustom (badge) yang terdaftar di sistem. Anda sedang dalam <strong>Mode Pantau</strong>.
        @endif
    </p>
</div>

@if (session('success'))
    <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

@if (! auth()->user()->isOwner())
    {{-- Banner Mode Pantau untuk Admin --}}
    <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50/80 p-4 text-sm text-blue-900 flex items-start gap-3">
        <span class="text-xl">👁️</span>
        <div>
            <p class="font-semibold">Mode Pantau (Admin)</p>
            <p class="text-xs text-blue-700 mt-0.5">
                Sebagai Admin, Anda dapat memantau daftar role dan melihat jumlah pengguna yang memilikinya.
                Hanya <strong>Owner</strong> yang memiliki wewenang untuk menambah, mengubah, atau menghapus role.
            </p>
        </div>
    </div>
@endif

@if (auth()->user()->isOwner())
    {{-- Form tambah role (khusus Owner) --}}
    <form method="POST" action="{{ route('admin.roles.store') }}"
          class="mb-6 grid gap-3 rounded-xl bg-white p-5 shadow-sm md:grid-cols-[1fr_90px_90px_1.5fr_auto] md:items-end">
        @csrf
        <div>
            <label class="mb-1 block text-xs font-semibold text-gray-600">Nama role</label>
            <input name="name" value="{{ old('name') }}" maxlength="40" required placeholder="mis. Kontributor"
                   class="w-full rounded-lg border px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold text-gray-600">Ikon</label>
            <input name="icon" value="{{ old('icon') }}" maxlength="16" placeholder="👑"
                   class="w-full rounded-lg border px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold text-gray-600">Warna</label>
            <input type="color" name="color" value="{{ old('color', '#e11d48') }}"
                   class="h-[38px] w-full rounded-lg border p-1">
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold text-gray-600">Deskripsi (opsional)</label>
            <input name="description" value="{{ old('description') }}" maxlength="160"
                   class="w-full rounded-lg border px-3 py-2 text-sm">
        </div>
        <button class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition">
            + Tambah Role
        </button>
    </form>
@endif

{{-- Daftar role --}}
<div class="overflow-x-auto rounded-xl bg-white shadow-sm">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3">Tampilan Role</th>
                @if (auth()->user()->isOwner())
                    <th class="px-4 py-3">Edit Role</th>
                @else
                    <th class="px-4 py-3">Deskripsi</th>
                @endif
                <th class="px-4 py-3 text-center">Jumlah Pengguna</th>
                @if (auth()->user()->isOwner())
                    <th class="px-4 py-3"></th>
                @endif
            </tr>
        </thead>
        <tbody>
        @forelse ($roles as $role)
            <tr class="border-t align-top hover:bg-gray-50/50">
                <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-bold text-white shadow-sm"
                          style="background: {{ $role->color }}">
                        @if ($role->icon)<span>{{ $role->icon }}</span>@endif {{ $role->name }}
                    </span>
                    @if ($role->description && auth()->user()->isOwner())
                        <p class="mt-1 text-xs text-gray-500">{{ $role->description }}</p>
                    @endif
                </td>

                @if (auth()->user()->isOwner())
                    {{-- Edit (Owner only) --}}
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="flex flex-wrap gap-2">
                            @csrf @method('PUT')
                            <input name="name" value="{{ $role->name }}" maxlength="40" required class="w-36 rounded-lg border px-2 py-1.5 text-sm">
                            <input name="icon" value="{{ $role->icon }}" maxlength="16" class="w-16 rounded-lg border px-2 py-1.5 text-sm">
                            <input type="color" name="color" value="{{ $role->color }}" class="h-[34px] w-12 rounded-lg border p-1">
                            <input name="description" value="{{ $role->description }}" maxlength="160" placeholder="Deskripsi"
                                   class="w-48 rounded-lg border px-2 py-1.5 text-sm">
                            <button class="rounded-lg border px-3 py-1.5 text-xs font-semibold hover:bg-gray-100">Simpan</button>
                        </form>
                    </td>
                @else
                    {{-- Read-only description (Admin) --}}
                    <td class="px-4 py-3 text-gray-600">
                        {{ $role->description ?: '—' }}
                    </td>
                @endif

                <td class="px-4 py-3 text-center font-medium">{{ $role->users_count }}</td>

                @if (auth()->user()->isOwner())
                    <td class="px-4 py-3 text-right">
                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
                              onsubmit="return confirm('Hapus role ini? Role akan dicabut dari semua pengguna.')">
                            @csrf @method('DELETE')
                            <button class="rounded-lg border px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Hapus</button>
                        </form>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ auth()->user()->isOwner() ? 4 : 3 }}" class="px-4 py-10 text-center text-gray-500">
                    Belum ada role kustom yang dibuat.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
