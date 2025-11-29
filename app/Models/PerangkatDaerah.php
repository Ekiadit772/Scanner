<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerangkatDaerah extends Model
{
    protected $table = 'perangkat_daerah';

    use SoftDeletes;

    protected $fillable = [
        'kode',
        'nama',
        'kadis_nama',
        'kadis_nip',
        'kadis_tte',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'perangkat_daerah_id');
    }
}
