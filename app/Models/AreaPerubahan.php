<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AreaPerubahan extends Model
{
    use SoftDeletes;

    protected $table = 'area_perubahan';

    protected $fillable = [
        'nama',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];
}
