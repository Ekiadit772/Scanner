<?php

namespace App\Services\Syarat;

use Illuminate\Http\Request;

interface SyaratServiceInterface
{
    public function store(Request $request, $permohonanId, $jenis);
    public function view($permohonanId, $jebis);
    public function getEditData($permohonanId, $jenis);
    public function update(Request $request, $permohonanId, $jenis);
}
