<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RepositorioConfiguracion;

class inicioController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function mostrarInicio(RepositorioConfiguracion $repoConfiguracion)
    {
        if (! $repoConfiguracion->getHabilitado()) {
            $datos["pagina"] = "inicio";
            $datos["descripcion"] = $repoConfiguracion->getDescripcion();

            return view('paginaMantenimiento',$datos);
        }
        return view('inicio');
    }
}
