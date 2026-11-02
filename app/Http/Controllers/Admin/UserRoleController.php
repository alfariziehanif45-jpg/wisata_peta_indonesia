<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    /**
     * Ubah role sistem pengguna (owner, admin, atau user biasa).
     * Hanya bisa diakses oleh Owner.
     */
    public function updateSystemRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', 'string', 'in:user,admin,owner'],
        ]);

        $newRole = $data['role'];

        // Cegah mencabut owner jika ini adalah satu-satunya Owner di sistem
        if ($user->isOwner() && $newRole !== 'owner') {
            $ownerCount = User::where('role', 'owner')->count();
            if ($ownerCount <= 1) {
                return back()->withErrors([
                    'role' => 'Tidak dapat mencabut role Owner: harus ada setidaknya satu Owner di sistem.',
                ]);
            }
        }

        $user->forceFill(['role' => $newRole])->save();

        $feedback = match ($newRole) {
            'owner' => "Role Owner berhasil diberikan ke {$user->name}. Pengguna ini sekarang memiliki hak akses penuh.",
            'admin' => "Role Admin berhasil diberikan ke {$user->name}. Pengguna ini sekarang dapat memantau sistem.",
            default => "Role Admin/Owner milik {$user->name} berhasil dicabut (kembali menjadi Pengguna Biasa).",
        };

        return back()->with('success', $feedback);
    }

    /**
     * Atur role kustom (badge) milik pengguna.
     * Hanya bisa diakses oleh Owner.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'roles'   => ['nullable', 'array'],
            'roles.*' => ['integer', 'exists:custom_roles,id'],
        ]);

        // sync: yang dicentang ditambahkan, yang tidak dicentang dicabut
        $user->customRoles()->sync($data['roles'] ?? []);

        return back()->with('success', "Role kustom untuk {$user->name} berhasil diperbarui.");
    }
}
