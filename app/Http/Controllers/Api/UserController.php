<?php

namespace App\Http\Controllers\Api;


use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use App\Models\PenyediaLayanan;
use App\Models\PerangkatDaerah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;

class UserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function index()
    {
        $roles = Role::where('name', '!=', 'Super Administrator')
            ->select('name', 'perangkat_daerah_id_required', 'penyedia_layanan_id_required')
            ->get();
        $opds = PerangkatDaerah::all();
        return view('users.index', compact('roles', 'opds'));
    }

    public function getUsers(Request $request)
    {
        $roleFilter = $request->role;
        $opdFilter  = $request->opd;
        $search = $request->get('search', null);

        $users = User::with(['roles:id,name,level', 'perangkatDaerah:id,nama', 'penyediaLayanan:id,nama_bidang'])
            ->select('id', 'name', 'email', 'nip', 'phone', 'perangkat_daerah_id', 'penyedia_layanan_id');

        if ($roleFilter) {
            $users->whereHas('roles', function ($q) use ($roleFilter) {
                $q->where('name', $roleFilter);
            });
        }

        if ($opdFilter) {
            if ($opdFilter === "Semua Perangkat Daerah") {
                $users->where('perangkat_daerah_id', -1);
            } else {
                $users->whereHas('perangkatDaerah', function ($q) use ($opdFilter) {
                    $q->where('nama', $opdFilter);
                });
            }
        }

        if ($search) {
            $users->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $users = $users->get();

        $currentUser = auth()->user();
        $authId = $currentUser->id;
        $authLevel = $currentUser->roles->first()->level ?? 0;

        $canEdit   = $currentUser->can('Pengguna Aplikasi;Edit');
        $canDelete = $currentUser->can('Pengguna Aplikasi;Hapus');
        $canView   = $currentUser->can('Pengguna Aplikasi;Lihat');

        $data = $users->map(function ($user, $index) use ($authId, $authLevel, $canEdit, $canDelete, $canView) {

            $hashedId = Hashids::encode($user->id);
            $role = $user->roles->first();
            $targetLevel = $role?->level ?? 0;

            $cannotModify = ($authId == $user->id || $targetLevel >= $authLevel);

            $buttons = "<div class='flex items-center'>";

            // =============================
            // EDIT BUTTON
            // =============================
            if (!$cannotModify && $canEdit) {
                $iconEdit = Blade::render('<x-icons.edit />');
                $buttons .= <<<HTML
                    <button type="button" onclick="editUser('{$hashedId}')" class="text-blue-500 mr-2" x-tooltip="Edit">
                        {$iconEdit}
                    </button>
                    HTML;
            }

            // =============================
            // DELETE BUTTON
            // =============================
            if (!$cannotModify && $canDelete) {
                $iconDelete = Blade::render('<x-icons.delete />');
                $buttons .= <<<HTML
                    <button type="button" onclick="deleteUser('{$hashedId}')" class="text-red-500 mr-2" x-tooltip="Hapus">
                        {$iconDelete}
                    </button>
                    HTML;
            }

            // =============================
            // VIEW BUTTON
            // =============================
            if ($canView) {
                $iconView = Blade::render('<x-icons.view/>');
                $buttons .= <<<HTML
                <button type="button" onclick="detailUser('{$hashedId}')" class="text-gray-600" x-tooltip="View">
                    {$iconView}
                </button>
                HTML;
            }

            $buttons .= "</div>";

            $roleName = $role?->name;
            $bidang = null;

            if ($roleName === "Super Administrator" && $user->penyedia_layanan_id == -1) {
                $bidang = "Semua Bidang";
            } elseif ($user->penyedia_layanan_id && $user->penyedia_layanan_id != -1) {
                $bidang = optional($user->penyediaLayanan)->nama_bidang;
            }

            return [
                'no'     => $index + 1,
                'nama'   => $user->name,
                'email'  => $user->email,
                'nip'    => $user->nip,
                'phone'  => $user->phone,
                'role'   => $roleName ?? '-',
                'id'     => $hashedId,

                'perangkat_daerah_id'   => $user->perangkat_daerah_id,
                'penyedia_layanan_id'   => $user->penyedia_layanan_id,

                'perangkat_daerah_nama' =>
                $user->perangkat_daerah_id == -1
                    ? "Semua Perangkat Daerah"
                    : optional($user->perangkatDaerah)->nama,

                'bidang_nama' => $bidang, // hanya muncul jika sesuai aturan

                'buttons' => $buttons,
            ];
        });

        return response()->json(['data' => $data]);
    }
    
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $trashedUsers = User::onlyTrashed()
            ->where(function ($q) use ($request) {
                $q->where('nip', $request->nip)
                    ->orWhere('email', $request->email)
                    ->orWhere('phone', $request->phone);
            })
            ->get();

        foreach ($trashedUsers as $trashedUser) {
            $trashedUser->forceDelete();
        }

        // RULE VALIDATOR DASAR
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'nip' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->whereNull('deleted_at'),
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users')->whereNull('deleted_at'),
            ],
            'password' => ['required'],
            'phone' => [
                'required',
                'string',
                'max:15',
                Rule::unique('users')->whereNull('deleted_at'),
            ],
            'role' => ['required', 'string'],
        ];

        // JALANKAN VALIDASI
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $role = Role::where('name', $request->role)->first();

        if (!$role) {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'role' => ['Role tidak ditemukan.']
                ]
            ], 422);
        }
        // VALIDASI PERANGKAT DAERAH
        if ($role->perangkat_daerah_id_required) {
            $rules['perangkat_daerah_id'] = [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    if ($value == -1) return true;
                    if (!PerangkatDaerah::where('id', $value)->exists()) {
                        $fail('Perangkat Daerah tidak ditemukan.');
                    }
                }
            ];
        }

        // VALIDASI PENYEDIA LAYANAN
        if ($role->penyedia_layanan_id_required) {
            $rules['penyedia_layanan_id'] = [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    if ($value == -1) return true;
                    if (!PenyediaLayanan::where('id', $value)->exists()) {
                        $fail('Bidang tidak ditemukan.');
                    }
                }
            ];
        }

        // JALANKAN VALIDASI
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // ===============================
        // CEK UNIK + JUMLAH PERANGKAT DAERAH (HANYA jika tidak pakai penyedia layanan)
        // ===============================
        if ($role->perangkat_daerah_id_required && !$role->penyedia_layanan_id_required) {

            $existing = User::whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role->name);
            })
                ->where('perangkat_daerah_id', $request->perangkat_daerah_id)
                ->first();

            if ($existing) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User dengan role dan perangkat daerah tersebut sudah ada.'
                ], 422);
            }

            $countUsers = User::whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role->name);
            })
                ->where('perangkat_daerah_id', $request->perangkat_daerah_id)
                ->count();

            if ($countUsers >= $role->jumlah_akun) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Jumlah akun untuk role {$role->name} di perangkat daerah ini sudah mencapai batas ({$role->jumlah_akun})."
                ], 422);
            }
        }

        // ===============================
        // CEK UNIK + JUMLAH PENYEDIA LAYANAN
        // ===============================
        if ($role->penyedia_layanan_id_required) {

            // CEK UNIK
            $existing = User::whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role->name);
            })
                ->where('perangkat_daerah_id', $request->perangkat_daerah_id)
                ->where('penyedia_layanan_id', $request->penyedia_layanan_id)
                ->first();

            if ($existing) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User dengan role, perangkat daerah, dan penyedia layanan tersebut sudah ada.'
                ], 422);
            }

            // CEK JUMLAH
            $countUsers = User::whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role->name);
            })
                ->where('perangkat_daerah_id', $request->perangkat_daerah_id)
                ->where('penyedia_layanan_id', $request->penyedia_layanan_id)
                ->count();

            if ($countUsers >= $role->jumlah_akun) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Jumlah akun untuk role {$role->name} pada penyedia layanan ini sudah mencapai batas ({$role->jumlah_akun})."
                ], 422);
            }
        }

        // CEK ROLE LEVEL
        $creator = Auth::user();
        if ($role->level > $creator->roleLevel()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak dapat membuat user dengan role lebih tinggi dari Anda.'
            ], 403);
        }

        $rolesWajibPD = ['Administrator PD', 'Kepala Dinas', 'Operator PMO'];

        if (in_array($request->role, $rolesWajibPD)) {

            if (!$request->perangkat_daerah_id || $request->perangkat_daerah_id == -1) {
                return response()->json([
                    'status' => 'error',
                    'errors' => [
                        'perangkat_daerah_id' => [
                            "Perangkat Daerah wajib dipilih untuk role {$request->role}"
                        ]
                    ]
                ], 422);
            }
        }

        if (!$role->perangkat_daerah_id_required) {
            $request->merge(['perangkat_daerah_id' => -1]);
        }

        if (!$role->penyedia_layanan_id_required) {
            $request->merge(['penyedia_layanan_id' => -1]);
        }


        // SIMPAN USER BARU
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'perangkat_daerah_id' => $request->perangkat_daerah_id,
            'penyedia_layanan_id' => $request->penyedia_layanan_id,
            'phone' => $request->phone,
            'nip' => $request->nip,
            'created_by' => Auth::user()->name,
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully.'
        ], 200);
    }

    public function edit($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        if (!$id) {
            abort(404, "Invalid ID");
        }

        $user = User::with('perangkatDaerah', 'penyediaLayanan')->find($id);
        $roles = Role::all();
        $userRole = $user->roles->pluck('name')->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found']);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'roles' => $roles,
                'userRole' => $userRole,
            ]
        ]);
    }

    public function update(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        if (!$id) {
            return response()->json([
                'status' => 'error',
                'errors' => ['id' => ['ID tidak valid']]
            ], 422);
        }

        // ===== VALIDASI DASAR =====
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id)->whereNull('deleted_at'),
            ],
            'nip' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($id)->whereNull('deleted_at'),
            ],
            'phone' => [
                'required',
                'string',
                'max:15',
                Rule::unique('users')->ignore($id)->whereNull('deleted_at'),
            ],
            'role' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // ===== CEK ROLE =====
        $role = Role::where('name', $request->role)->first();
        if (!$role) {
            return response()->json([
                'status' => 'error',
                'errors' => ['role' => ['Role tidak ditemukan.']]
            ], 422);
        }

        // ===== VALIDASI DINAMIS PD & BIDANG BERDASARKAN ROLE =====
        if ($role->perangkat_daerah_id_required) {

            if (
                $request->perangkat_daerah_id != -1 &&
                !PerangkatDaerah::where('id', $request->perangkat_daerah_id)->exists()
            ) {

                return response()->json([
                    'status' => 'error',
                    'errors' => ['perangkat_daerah_id' => ['Perangkat Daerah tidak ditemukan.']]
                ], 422);
            }
        } else {
            // ROLE TIDAK BUTUH PD → PAKSA -1
            $request->merge(['perangkat_daerah_id' => -1]);
        }


        if ($role->penyedia_layanan_id_required) {

            if (
                $request->penyedia_layanan_id != -1 &&
                !PenyediaLayanan::where('id', $request->penyedia_layanan_id)->exists()
            ) {

                return response()->json([
                    'status' => 'error',
                    'errors' => ['penyedia_layanan_id' => ['Bidang tidak ditemukan.']]
                ], 422);
            }
        } else {
            // ROLE TIDAK BUTUH BIDANG → PAKSA -1
            $request->merge(['penyedia_layanan_id' => -1]);
        }


        // ===== TEMUKAN USER =====
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'errors' => ['user' => ['User tidak ditemukan']]
            ], 422);
        }

        // ===== CEK LEVEL ROLE =====
        $auth = Auth::user();
        if ($auth->roleLevel() < $user->roleLevel()) {
            return response()->json([
                'status' => 'error',
                'errors' => ['role' => ['Tidak boleh mengubah role lebih tinggi.']]
            ], 403);
        }

        // ===== UPDATE =====
        $user->update([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'phone' => $request->phone,
            'nip' => $request->nip,
            'perangkat_daerah_id' => $request->perangkat_daerah_id,
            'penyedia_layanan_id' => $request->penyedia_layanan_id,
            'updated_by' => $auth->name,
        ]);

        $user->syncRoles([$request->role]);

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil diupdate!',
        ]);
    }


    public function destroy(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        if (!$id) {
            abort(404, "Invalid ID");
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found.']);
        }

        $authUser = Auth::user();
        $targetUser = User::find($id);

        if ($authUser->id === $targetUser->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kamu tidak bisa menghapus dirimu sendiri.'
            ], 403);
        }

        if ($authUser->roleLevel() < $user->roleLevel()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak dapat menghapus user dengan level lebih tinggi dari Anda.'
            ], 403);
        }

        // Simpan siapa yang menghapus
        $user->deleted_by = Auth::user()->name;
        $user->save();

        // Hapus relasi role dan permission
        $user->syncRoles([]);        // detach semua role
        $user->syncPermissions([]);  // detach semua permission

        // Soft delete user
        $user->delete();

        return response()->json(['status' => 'success', 'message' => 'User deleted successfully.']);
    }

    public function getBidang(Request $request, $perangkatDaerahId)
    {
        $search = $request->get('q');

        $data = PenyediaLayanan::where('perangkat_daerah_id', $perangkatDaerahId)
            ->when($search, fn($q) => $q->where('nama_bidang', 'like', "%{$search}%"))
            ->select('id', 'nama_bidang')
            ->get()
            ->map(fn($item) => ['id' => $item->id, 'text' => $item->nama_bidang]);

        return response()->json(['results' => $data]);
    }
}
