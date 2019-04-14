<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RepositorioPermiso;
use App\Models\RepositorioConfiguracion;

class BuscadorInstitucionesController extends Controller
{
    public function inicio(RepositorioConfiguracion $repoConfiguracion, RepositorioPermiso $repoPermiso)
    {
        $datos["descripcion"] = $repoConfiguracion->getDescripcion();
        $datos["limite"] = $repoConfiguracion->getLimite();
        $datos["logueado"] = (session("id")) ? true : false;

        if (session("id")) {
            $id = session("id");

            $datos["modulos"] = $repoPermiso->modulos_id_usuario_admin($id,0);
            $datos["modulosAdministracion"] = $repoPermiso->modulos_id_usuario_admin($id,1);
            $datos["username"] = session("username");
            $datos["pagina"] = 'layoutLogueado';
            $datos["permisos"] = $repoPermiso->permisos_id_usuario_modulo($id,"institucion");
        }else {
            $datos["pagina"] = 'inicio';
            $datos["permisos"] = ["index"];
        }
        return view('buscadorInstituciones', $datos);
    }
}
