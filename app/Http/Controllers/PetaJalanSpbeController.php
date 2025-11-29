<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PetaJalanSpbeController extends Controller
{
    public function index(){
        return view('peta_jalan_spbe.index');
    }
}
