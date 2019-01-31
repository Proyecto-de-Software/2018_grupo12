<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only('mostrarLogin');
        $this->middleware('guest')->only('autenticar');
        $this->middleware('auth')->only('cerrarSesion');
    }

    public function mostrarLogin()
    {
        return view('login');
    }

    public function autenticar(Request $request)
    {
        $datos = $request->only('usuario', 'contrasena');

        $credenciales["username"] = $datos["usuario"];
        $credenciales["password"] = $datos["contrasena"];

        if (Auth::attempt($credenciales)) {
            $credenciales["activo"] = 1;
            if (Auth::attempt($credenciales)) {
                session(["username" => $credenciales["username"], "id" => Auth::id()]);
                return array('estado' => "success");
            }else {
                return array('estado' => "error", 'mensaje' => "El usuario se encuentra bloqueado");
            }
        }else{
            return array('estado' => "error", 'mensaje' => "Usuario y contraseña incorrectos");
        }
    }

    public function cerrarSesion()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('inicio');
    }
}
