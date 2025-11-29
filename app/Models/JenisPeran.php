<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisPeran extends Model
{
    protected $table = 'jenis_peran';

    use SoftDeletes;

    protected $fillable = [
        'nama',
        'peran',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
