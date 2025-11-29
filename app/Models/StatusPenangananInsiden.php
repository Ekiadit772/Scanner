<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusPenangananInsiden extends Model
{
    protected $table = 'status_penanganan_insiden';

    use SoftDeletes;

    protected $fillable = [
        'nama',
        'keterangan',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function insiden()
    {
        return $this->hasMany(InsidenLayanan::class, 'status_penanganan_insiden_id', 'id');
    }
}
