@extends('layout')

@section('titulo')
    Oops!
@endsection

@section('panel_derecho')
    <button type="button" name="button" onclick="location.href='inicio'" class="btn btn-outline-primary ml-0 ml-lg-5">Inicio</button>
@endsection

@section('buscador')
    <div class="ml-auto">

    </div>
@endsection

@section('cuerpo')
    <div class="container my-5">
        <div class="row justify-content-center">
          <div class="col">
            <h1 class="textcenter">Oops!</h1>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col">
            <p class="textcenter tamanofuente">La operacion solicitada no pudo ser realizada, vuelva a intentar mas tarde</p>
          </div>
        </div>
    </div>
@endsection
