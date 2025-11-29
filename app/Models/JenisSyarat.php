<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisSyarat extends Model
{
    use SoftDeletes;
    
    protected $table = 'jenis_syarat';

    protected $fillable = [
        'nama',
        'kelompok',
        'keterangan',
        'is_internal',
        'is_aktif',
        'is_custom',
    ];
}
