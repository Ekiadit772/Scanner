<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriPerubahan extends Model
{
    protected $table = 'kategori_perubahan';

    use SoftDeletes;

    protected $fillable = [
        'nama',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
