<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends SpatieRole
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'guard_name',
        'level',
        'jumlah_akun',
        'perangkat_daerah_id_required',
        'penyedia_layanan_id_required',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $dates = ['deleted_at'];

    public function delete()
    {
        $this->deleted_by = auth()->user()->name ?? null;
        $this->save();

        return parent::delete(); // Laravel akan isi deleted_at otomatis
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
