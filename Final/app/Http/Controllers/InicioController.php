<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class inicioController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only('mostrarInicio');
    }

    public function mostrarInicio()
    {
        return view('inicio');
    }
}
