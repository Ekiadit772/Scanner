<?php

namespace App\Http\Controllers;

use App\Models\AreaPerubahan;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class AreaPerubahanController extends Controller
{
    public function getAreaPerubahanData()
    {
        $areaPerubahan = AreaPerubahan::select('id', 'nama', 'is_aktif')->get();

        $data = $areaPerubahan->map(function ($ap, $index) {

            $hashedId = Hashids::encode($ap->id);

            return [
                $ap->nama,
                $ap->is_aktif,
                $hashedId,
            ];
        });

        return response()->json(['data' => $data]);
    }
}
