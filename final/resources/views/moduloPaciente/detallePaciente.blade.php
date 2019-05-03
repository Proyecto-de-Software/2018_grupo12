@php
    $opcion = ["0" => "No", "1" => "Si"]
@endphp

<div class="container">
  <div class="row justify-content-center mt-5 mb-5">
    <div class="col-12 col-md-10 col-lg-6">
      <div class="row p-1 mb-1 bordeclass">
        <div class="">
          Nombre:
        </div>
        <div class="ml-auto breakclass">
          {{ ucfirst(strtolower($nombre)) }}
        </div>
      </div>
      <div class="row p-1 mb-1 mb-1 bordeclass">
        <div class="">
          Apellido:
        </div>
        <div class="ml-auto breakclass">
          {{ ucfirst(strtolower($apellido))}}
        </div>
      </div>
      <div class="row p-1 mb-1 mb-1 bordeclass">
        <div class="">
          Fecha de Nacimiento:
        </div>
        <div class="ml-auto">
          {{ date('d-m-Y', strtotime($fecha_nac)) }}
        </div>
      </div>
      <div class="row p-1 mb-1 mb-1 bordeclass">
        <div class="">
          Lugar de nacimiento:
        </div>
        <div class="ml-auto breakclass">
          {{ ucwords($lugar_nac) }}
        </div>
      </div>
      <div class="row p-1 mb-1 mb-1 bordeclass">
        <div class="">
          Partido:
        </div>
        <div class="ml-auto breakclass">
          {{ ucwords($partido) }}
        </div>
      </div>
      <div class="row p-1 mb-1 mb-1 bordeclass">
        <div class="">
          Localidad:
        </div>
        <div class="ml-auto breakclass">
          {{ ucwords($localidad) }}
        </div>
      </div>
      <div class="row p-1 mb-1 mb-1 bordeclass">
        <div class="">
          Region sanitaria:
        </div>
        <div class="ml-auto breakclass">
          {{ $region_sanitaria }}
        </div>
      </div>
      <div class="row p-1 mb-1 mb-1 bordeclass">
        <div class="">
          Domicilio:
        </div>
        <div class="ml-auto breakclass">
          {{ $domicilio }}
        </div>
      </div>
      <div class="row p-1 mb-1 mb-1 bordeclass">
        <div class="">
          Genero:
        </div>
        <div class="ml-auto breakclass">
          {{ ucfirst(strtolower($genero)) }}
        </div>
      </div>
      <div class="row p-1 mb-1 mb-1 bordeclass">
        <div class="">
          Tiene en su poder su documento:
        </div>
        <div class="ml-auto breakclass">
          {{ $opcion[$tiene_documento] }}
        </div>
      </div>
      <div class="row p-1 mb-1 mb-1 bordeclass">
        <div class="">
          Tipo de documento:
        </div>
        <div class="ml-auto breakclass">
          {{ $tipo_doc }}
        </div>
      </div>
      <div class="row p-1 mb-1 mb-1 bordeclass">
        <div class="">
          Numero de documento:
        </div>
        <div class="ml-auto breakclass">
          {{ $numero }}
        </div>
      </div>
      <div class="row p-1 mb-1 mb-1 bordeclass">
        <div class="">
          Numero de historia clinica:
        </div>
        <div class="ml-auto breakclass">
          {{ $nro_historia_clinica }}
        </div>
      </div>
      <div class="row p-1 mb-1 bordeclass">
        <div class="">
          Numero de carpeta:
        </div>
        <div class="ml-auto breakclass">
          {{ $nro_carpeta }}
        </div>
      </div>
      <div class="row p-1 mb-1 bordeclass">
        <div class="">
          Telelefono/Celular:
        </div>
        <div class="ml-auto breakclass">
          {{ $tel }}
        </div>
      </div>
      <div class="row p-1 mb-1 bordeclass">
        <div class="">
          Obra social:
        </div>
        <div class="ml-auto breakclass">
          {{ $obra_social }}
        </div>
      </div>
    </div>
  </div>
</div>
