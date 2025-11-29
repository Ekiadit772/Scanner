<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Blade;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;

class RoleController extends Controller
{
    public function index()
    {
        return view('roles.index');
    }

    public function getRoles()
    {
        $roles = Role::select('id', 'name')->get();

        $data = $roles->map(function ($role, $index) {

            $hashedId = Hashids::encode($role->id);

            $buttons = "<div class='flex items-center'>";

            // === Tidak bisa diubah (Super Administrator) ===
            if (strtolower($role->name) === 'super administrator') {
                $buttons .= "<span class='text-gray-400 italic'>Tidak dapat diubah</span></div>";
                return [
                    $index + 1,
                    $role->name,
                    $hashedId,
                    $buttons
                ];
            }

            $currentUser = auth()->user();
            $canEdit = $currentUser->can('Pengguna Aplikasi;Edit');

            // === TOMBOL PERMISSION ===
            if($canEdit){
                $iconPermission = Blade::render('<x-icons.view />');
                $buttons .= <<<HTML
                        <button onclick="loadPermissions('{$hashedId}', '{$role->name}')" x-tooltip="Permission">
                            {$iconPermission}
                        </button>
                    HTML;
    
                $buttons .= "</div>";
            }

            return [
                $index + 1,
                $role->name,
                $hashedId,           // ← ID SUDAH HASHED
                $buttons             // ← BUTTON DIKIRIM DARI CONTROLLER
            ];
        });

        return response()->json(['data' => $data]);
    }


    // public function create()
    // {
    //     return view('roles.create');
    // }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => [
    //             'required',
    //             'string',
    //             'max:255',
    //             Rule::unique('roles', 'name')->whereNull('deleted_at'),
    //         ],
    //         'level' => [
    //             'required',
    //             'integer',
    //             Rule::in([10, 20, 30, 40, 50, 60, 70, 80, 90, 100]), // hanya angka ini yang valid
    //         ],
    //         'jumlah_akun' => [
    //             'required',
    //             'integer',
    //             'min:1',
    //         ],
    //         'perangkat_daerah_id_required' => [
    //             'required',
    //             'in:0,1',
    //         ],

    //         'penyedia_layanan_id_required' => [
    //             'required',
    //             'in:0,1',
    //         ],
    //     ], [
    //         'name.required' => 'Nama role wajib diisi.',
    //         'name.unique' => 'Nama role sudah digunakan.',
    //         'name.max' => 'Nama role tidak boleh lebih dari 255 karakter.',
    //         'level.required' => 'Level wajib dipilih.',
    //         'level.integer' => 'Level harus berupa angka.',
    //         'level.in' => 'Level tidak valid. Pilih antara 10, 20, 30, 40, 50, 60, 70, 80, 90, atau 100.',
    //         'jumlah_akun.required' => 'Jumlah akun wajib diisi.',
    //         'perangkat_daerah_id_required' => 'User dengan Role ini Wajib isi Perangkat Daerah wajib diisi.',
    //         'penyedia_layanan_id_required' => 'User dengan Role ini Wajib isi Penyedia Layanan wajib diisi.'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     // Cek apakah role dengan nama itu sudah pernah ada dan dihapus
    //     $trashedRole = Role::onlyTrashed()->where('name', $request->name)->first();

    //     if ($trashedRole) {
    //         // Kembalikan (restore) role yang dihapus
    //         $trashedRole->restore();
    //         $trashedRole->update([
    //             'updated_by' => Auth::user()->name,
    //             'deleted_by' => null,
    //             'level' => $request->level,
    //             'jumlah_akun' => $request->jumlah_akun,
    //             'perangkat_daerah_id_required' => $request->perangkat_daerah_id_required,
    //             'penyedia_layanan_id_required' => $request->penyedia_layanan_id_required
    //         ]);

    //         return response()->json(['status' => 'success', 'message' => 'Role berhasil ditambahkan']);
    //     }

    //     Role::create([
    //         'name' => $request->name,
    //         'guard_name' => 'web',
    //         'level' => $request->level,
    //         'jumlah_akun' => $request->jumlah_akun,
    //         'perangkat_daerah_id_required' => $request->perangkat_daerah_id_required,
    //         'penyedia_layanan_id_required' => $request->penyedia_layanan_id_required,
    //         'created_by' => Auth::user()->name,
    //         'updated_by' => Auth::user()->name,
    //     ]);

    //     return response()->json(['status' => 'success', 'message' => 'Role berhasil ditambahkan']);
    // }

    // public function edit($id)
    // {
    //     $role = Role::find($id);

    //     if (!$role) {
    //         abort(404, 'Role tidak ditemukan.');
    //     }

    //     if (strtolower($role->name) === 'super administrator') {
    //         abort(403, 'Role Super Administrator tidak dapat diedit.');
    //     }

    //     // return view('roles.edit', compact('role'));
    //     return response()->json(['status' => 'success', 'data' => [
    //         'roles' => $role
    //     ]]);
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'level' => [
    //             'required',
    //             'integer',
    //             Rule::in([10, 20, 30, 40, 50, 60, 70, 80, 90, 100]), // hanya angka ini yang valid
    //         ],
    //         'jumlah_akun' => [
    //             'required',
    //             'integer',
    //             'min:1',
    //         ],
    //         'perangkat_daerah_id_required' => [
    //             'required',
    //             'in:0,1',
    //         ],

    //         'penyedia_layanan_id_required' => [
    //             'required',
    //             'in:0,1',
    //         ],
    //     ], [
    //         'name.required' => 'Nama role wajib diisi.',
    //         'name.max' => 'Nama role tidak boleh lebih dari 255 karakter.',
    //         'level.required' => 'Level wajib dipilih.',
    //         'level.integer' => 'Level harus berupa angka.',
    //         'level.in' => 'Level tidak valid. Pilih antara 10, 20, 30, 40, 50, 60, 70, 80, 90, atau 100.',
    //         'jumlah_akun.required' => 'Jumlah akun wajib diisi.',
    //         'perangkat_daerah_id_required' => 'User dengan Role ini Wajib isi Perangkat Daerah wajib diisi.',
    //         'penyedia_layanan_id_required' => 'User dengan Role ini Wajib isi Penyedia Layanan wajib diisi.'
    //     ]);

    //     $role = Role::withTrashed()->find($id);

    //     if (!$role) {
    //         // return redirect()->route('roles.index')->with('error', 'Role tidak ditemukan.');
    //         return response()->json(['status' => 'error', 'message' => 'Role tidak ditemukan']);
    //     }

    //     if (strtolower($role->name) === 'super administrator') {
    //         return response()->json(['status' => 'success', 'message' => 'Role Super Administrator tidak dapat diubah']);
    //     }

    //     // Cek apakah nama baru digunakan oleh role lain yang tidak dihapus
    //     $existing = Role::where('name', $request->name)
    //         ->where('guard_name', 'web')
    //         ->whereNull('deleted_at')
    //         ->where('id', '!=', $id)
    //         ->first();

    //     if ($existing) {
    //         return redirect()->route('roles.index')->with('error', 'Nama role sudah digunakan.');
    //     }

    //     // Jika nama baru sama dengan role yang pernah dihapus, hapus permanen yang lama agar tidak bentrok unique key
    //     $trashedSameName = Role::onlyTrashed()
    //         ->where('name', $request->name)
    //         ->where('guard_name', 'web')
    //         ->first();

    //     if ($trashedSameName) {
    //         $trashedSameName->forceDelete();
    //     }

    //     // Update nama role
    //     $role->update([
    //         'name' => $request->name,
    //         'level' => $request->level,
    //         'jumlah_akun' => $request->jumlah_akun,
    //         'perangkat_daerah_id_required' => $request->perangkat_daerah_id_required,
    //         'penyedia_layanan_id_required' => $request->penyedia_layanan_id_required,
    //         'updated_by' => Auth::user()->name,
    //     ]);

    //     // return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    //     return response()->json(['status' => 'success', 'message' => 'Role berhasil diperbarui']);
    // }

    // public function destroy($id)
    // {
    //     $role = Role::find($id);

    //     if (!$role) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Role tidak ditemukan.'
    //         ]);
    //     }

    //     // Cegah hapus role Administrator
    //     if (strtolower($role->name) === 'administrator') {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Role Administrator tidak dapat dihapus.'
    //         ], 403);
    //     }

    //     $role->deleted_by = Auth::user()->name;
    //     $role->save();
    //     $role->delete();

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Role berhasil dihapus.'
    //     ]);
    // }

    public function apiPermissions($hashId)
    {
        $id = Hashids::decode($hashId)[0];

        $role = Role::findOrFail($id);

        $permissions = Permission::where('is_aktif', 1)
            ->orderBy('group_order')
            ->orderBy('name')
            ->get();

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        $grouped = [];

        foreach ($permissions as $perm) {

            $parts = explode(';', $perm->name);
            $rawTitle = trim($parts[0] ?? '');
            $action   = ucfirst(trim($parts[1] ?? ''));

            $title = "{$perm->group_name} : " . ucwords($rawTitle);

            $grouped[$perm->group_order][$title][] = [
                'id'        => $perm->id,
                'title'     => $title,
                'action'    => $action,
                'full'      => $perm->name,
                'checked'   => in_array($perm->name, $rolePermissions),
                'group'     => $perm->group_name,
                'order'     => $perm->group_order,
                'is_aktif'  => $perm->is_aktif,
            ];
        }

        ksort($grouped);

        $final = [];
        foreach ($grouped as $orderGroup) {
            foreach ($orderGroup as $title => $items) {
                // sort actions A-Z
                usort($items, fn($a, $b) => strcmp($a['action'], $b['action']));
                $final[$title] = $items;
            }
        }

        return response()->json([
            'role' => [
                'id' => Hashids::encode($role->id),
                'name' => $role->name,
            ],
            'permissions' => $final,
        ]);
    }

    // === API: Update permission dari AJAX ===
    public function apiUpdatePermissions(Request $request, $hashId)
    {
        $id = Hashids::decode($hashId)[0];

        $role = Role::findOrFail($id);

        if (strtolower($role->name) === 'administrator') {
            return response()->json([
                'status' => 'error',
                'message' => 'Role Administrator tidak dapat diubah.'
            ], 403);
        }

        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return response()->json([
            'status' => 'success',
            'message' => 'Permission role berhasil diperbarui.'
        ]);
    }
}
