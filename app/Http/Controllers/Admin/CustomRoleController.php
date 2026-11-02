<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomRoleController extends Controller
{
    public function index()
    {
        $roles = CustomRole::withCount('users')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        CustomRole::create($this->validated($request));

        return back()->with('success', 'Role berhasil dibuat.');
    }

    /**
     * Buat role baru langsung dari daftar pengguna,
     * lalu otomatis diberikan ke user tersebut (role lama tetap dipertahankan).
     */
    public function storeAndAssign(Request $request, User $user)
    {
        $role = CustomRole::create($this->validated($request));

        $user->customRoles()->syncWithoutDetaching([$role->id]);

        return back()->with('success', 'Role "' . $role->name . '" dibuat dan diberikan ke ' . $user->name . '.');
    }

    public function update(Request $request, CustomRole $role)
    {
        $role->update($this->validated($request, $role));

        return back()->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(CustomRole $role)
    {
        $role->delete(); // pivot ikut terhapus (cascade)

        return back()->with('success', 'Role dihapus dari semua pengguna.');
    }

    private function validated(Request $request, ?CustomRole $role = null): array
    {
        return $request->validate([
            'name'        => ['required', 'string', 'max:40', Rule::unique('custom_roles', 'name')->ignore($role?->id)],
            'icon'        => ['nullable', 'string', 'max:16'],
            'color'       => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'description' => ['nullable', 'string', 'max:160'],
        ]);
    }
}