<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::fallback('InicioController@mostrarInicio');

Route::prefix('/grupo12/final/public')->group(function () {
    // Inicio/Home y login
    Route::get('/', 'InicioController@mostrarInicio');

    Route::get('/login', 'LoginController@mostrarLogin')
        ->name('login');

    Route::post('/autenticar', 'LoginController@autenticar');

    Route::get('/cerrarSesion', 'LoginController@cerrarSesion');

    Route::get('/home', 'HomeController@mostrarHome')
        ->name('home');

    //Usuarios
    Route::get('/users', 'UsuariosController@inicio');

    Route::get('/usuarios/{id}/bloquear', 'UsuariosController@bloquearUsuario');

    Route::get('/usuarios/{id}/activar', 'UsuariosController@activarUsuario');

    Route::get('/usuarios/{id}/roles', 'UsuariosController@panelRoles');

    Route::put('/usuarios/{id}/roles/{idRol}/agregar', 'UsuariosController@agregarRol');

    Route::put('/usuarios/{id}/roles/{idRol}/quitar', 'UsuariosController@quitarRol');

    Route::resource('usuarios', 'UsuariosController')->except([
        'show', 'destroy', 'create'
    ]);;

    //Configuracion
    Route::get('/configuracion', 'ConfiguracionController@inicio');

    Route::put('/configuracion', 'ConfiguracionController@guardarConfiguracion');

    //Pacientes
    Route::get('/patients', 'PacientesController@inicio');

    Route::get('/pacientes/{id}/tieneConsultas', 'PacientesController@pacienteTieneConsultas');

    Route::post('/pacientes/simple', 'PacientesController@agregarPacienteSimple');

    Route::post('/pacientes/completo', 'PacientesController@agregarPacienteCompleto');

    Route::resource('pacientes', 'PacientesController')->except([
        'store', 'create'
    ]);;

    //Consultas
    Route::get('/consultations', 'ConsultasController@inicio');

    Route::get('/consultas/pacientes', 'ConsultasController@pacientesParaConsulta');

    Route::get('/pacientes/{id}/coordenadasDerivaciones', 'ConsultasController@coordenadasDerivaciones');

    Route::resource('consultas', 'ConsultasController')->except([
        'create'
    ]);;
});
