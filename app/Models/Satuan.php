<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Satuan extends Model
{
    protected $table = 'satuan';

    use SoftDeletes;

    protected $fillable = [
        'nama',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
