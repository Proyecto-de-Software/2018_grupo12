<?php

namespace App\Http\Controllers;

use App\Models\Institucion;
use App\Models\RepositorioApi;
use Illuminate\Http\Request;
use Validator;

class ApiController extends Controller
{
    protected $repositorio;

    public function __construct(RepositorioApi $repositorio)
    {
        $this->repositorio = $repositorio;

        $this->middleware('auth:api')->only(['store', 'update']);
        $this->middleware('authorizeApi:institucion_new')->only(['store']);
        $this->middleware('authorizeApi:institucion_update')->only(['update']);
    }

    public function index()
    {
        return response()->json($this->repositorio->todos(), 200);
    }
    public function institucion($id)
    {
        return response()->json($this->repositorio->institucion_id($id), 200);
    }
    public function institucionesRegion($regionSanitaria)
    {
        return response()->json($this->repositorio->region_nombre($regionSanitaria), 200);
    }
    private function validar($request)
    {
        return Validator::make($request->all(),
            [
                'nombre' => 'required | regex:/^[a-zA-Z ]+$/',
                'director' => 'required | regex:/^[a-zA-Z ]+$/',
                'direccion' => 'required',
                'telefono' => 'required',
                'localidad_id' => 'required | numeric',
                'tipo_institucion_id' => 'required | numeric',
            ]);
    }
    public function store(Request $request)
    { /*se espera nombre, director, direccion, telefono, localidad_id, tipo_institucion_id*/
        $val = $this->validar($request);
        if ($val->fails()) {
            return response()->json(["error" => "campos incorrectos"], 400);
        }
        if (!$this->repositorio->localidadExiste($request->input('localidad_id'))) {
            return response()->json(["error" => "la localidad de la institucion no existe"], 400);
        }
        if (!$this->repositorio->tipoInstitucionExiste($request->input('tipo_institucion_id'))) {
            return response()->json(["error" => "el tipo de institucion no existe"], 400);
        }
        $institucion = new Institucion("", $request->input('nombre'), $request->input('director'), $request->input('direccion'),
            $request->input('telefono'), $request->input('localidad_id'), $request->input('tipo_institucion_id'));
        $this->repositorio->insertarInstitucion($institucion);
        return response()->json(["success" => "institucion agregada correctamente"], 200);
    }
    public function update($id, Request $request)
    {
        /*se espera nombre, director, direccion, telefono, localidad_id, tipo_institucion_id y el id de la
        institucion a actualizar en url*/
        $val = $this->validar($request);
        if ($val->fails()) {
            return response()->json(["error" => "campos incorrectos"], 400);
        }
        if (!$this->repositorio->localidadExiste($request->input('localidad_id'))) {
            return response()->json(["error" => "la localidad de la institucion no existe"], 400);
        }
        if (!$this->repositorio->tipoInstitucionExiste($request->input('tipo_institucion_id'))) {
            return response()->json(["error" => "el tipo de institucion no existe"], 400);
        }
        $institucion = new Institucion($id, $request->input('nombre'), $request->input('director'), $request->input('direccion'),
            $request->input('telefono'), $request->input('localidad_id'), $request->input('tipo_institucion_id'));
        $this->repositorio->actualizarInstitucion($institucion);
        return response()->json(["success" => "institucion actualizada correctamente"], 200);
    }

}
