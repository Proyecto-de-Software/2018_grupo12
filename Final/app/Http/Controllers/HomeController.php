<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\RepositorioConfiguracion;
use App\Models\RepositorioPermiso;

class HomeController extends Controller
{
    protected $repoPermiso;
    protected $repoConfig;

    public function __construct(RepositorioConfiguracion $repoConfig, RepositorioPermiso $repoPermiso)
    {
        $this->repoPermiso = $repoPermiso;
        $this->repoConfig = $repoConfig;

        $this->middleware('auth')->only('mostrarHome');
    }

    public function mostrarHome()
    {
        $id = Auth::id();

        $datos["tituloPag"] = $this->repoConfig->getTitulo();
        $datos["modulos"] = $this->repoPermiso->modulos_id_usuario_admin($id,0);
        $datos["modulosAdministracion"] = $this->repoPermiso->modulos_id_usuario_admin($id,1);
        $datos["username"] = session("username");

        return view('home', $datos);
    }
}
