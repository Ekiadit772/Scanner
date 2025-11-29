<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SyaratController extends Controller
{
    public function form($id)
    {
        $mapping = [
            1 => 'identitas_aplikasi',
            2 => 'kak',
            3 => 'surat_rekomendasi',
            4 => 'dokumen_teknis_pengembangan',
            5 => 'dokumen_pengujian',
            6 => 'nda',
        ];
        
        if (!isset($mapping[$id])) {
        $mapping[$id] = 'syarat_lainnya';
        }

        $view = $mapping[$id];
        return view("forms.$view", ['syarat_id' => $id]);
    }
}
