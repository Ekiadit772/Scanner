<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisLayanan extends Model
{
    protected $table = 'jenis_layanan';

    use SoftDeletes;

    protected $fillable = [
        'nama',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
