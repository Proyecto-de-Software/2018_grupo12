if ($("#tabPacientes")[0]) {
  var tipoBusqueda = document.getElementById("opcionPorDefecto").value;
  var nombre = "";
  var apellido = "";
  var tipoDoc = "";
  var nroDoc = "";
  var nroHistoriaClinica = "";
}

// ------------------ Alertas ------------------
//Funcion para armar la alerta
function mostrarAlerta(texto, tipo){
  new Message(texto, {
    duration: 4000,
    type: tipo,
    class : 'alerta'
  }).show();
}

// ------------------ Busqueda y Paginado ------------------

//Para mostrar el formulario correspondiente al tipo de Busqueda
function mostrarFormBusqueda() {
  if (tipoBusqueda != "no_aplica") {
    $("#" + tipoBusqueda).css({"display": "none"});
  }

  if (this.value != "no_aplica") {
    $("#" + this.value).css({"display": "contents"});
    tipoBusqueda = this.value;
  }else {
    $("#btnBuscar")[0].onclick();
  }
}

//Se ejecuta cuando se busca por algun campo
function buscar(){
  //Actualizo criterios de busqueda
  tipoBusqueda = $("#tipoBusqueda")[0].value;
  nombre = $("#bus_nombre")[0].value;
  apellido = $("#bus_apellido")[0].value;
  tipoDoc = $("#bus_tipoDoc")[0].value;
  nroDoc = $("#bus_nroDoc")[0].value;
  nroHistoriaClinica = $("#bus_nroHistoriaClinica")[0].value;


  //Desactivo pagina actual
  document.getElementsByClassName("page-item active")[0].className = "page-item";

  //Valores por defecto para el indice
  $("#anterior")[0].className = "page-item disabled";
  $("#inicio")[0].className = "page-item active";
  $("#medio")[0].className = "page-item";
  $("#final")[0].className = "page-item";
  $("#siguiente")[0].className = "page-item";

  $("#btnInicio")[0].innerHTML = "1";
  $("#btnMedio")[0].innerHTML = "2";
  $("#btnFinal")[0].innerHTML = "3";

  //Click al  boton "1" para disparar la busqueda
  $("#btnInicio")[0].onclick();
}

//Se ejecuta cuando se cliquea la pagina izquierda
function clickInicio(){
  //Desactivo pagina actual
  document.getElementsByClassName("page-item active")[0].className = "page-item";

  //Guardo pagina requerida
  var pagina = this.innerHTML;

  //Actualizo indice segun corresponda
  if (pagina != "1") {
    $("#medio")[0].className = "page-item active";

    this.innerHTML = parseInt(this.innerHTML) - 1;
    $("#btnMedio")[0].innerHTML = parseInt($("#btnMedio")[0].innerHTML) - 1;
    $("#btnFinal")[0].innerHTML = parseInt($("#btnFinal")[0].innerHTML) - 1;
  }else {
    $("#inicio")[0].className = "page-item active";
  }

  if (pagina == "1") {
    $("#anterior")[0].className = "page-item disabled";
  }

  //Cosulta para cargar la pagina requerida
  $.ajax({
    url : '?action=cargarPaginaPacientes',
    data : { tipoBusqueda : tipoBusqueda, nombre : nombre, apellido : apellido, pagina : pagina,
             tipoDoc : tipoDoc, nroDoc : nroDoc, nroHistoriaClinica : nroHistoriaClinica },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaPacientes")[0].innerHTML = '<td align="center" colspan="7">No hay pacientes para mostrar</td>';
          if (pagina == "1") {
            $("#medio")[0].className = "page-item disabled";
          }
          $("#final")[0].className = "page-item disabled";
          $("#siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#cuerpoTablaPacientes")[0].innerHTML = respuesta.contenido;
          if (pagina == 1) {
            $("#medio")[0].className = "page-item";
          }
          $("#final")[0].className = "page-item";
          $("#siguiente")[0].className = "page-item";
          asignarFuncionesALasOperaciones();
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
          $("#cuerpoTablaPacientes")[0].innerHTML = '<td align="center" colspan="7">No se pudo realizar la operacion solicitada</td>';
      }
    }
  });
}

//Se ejecuta cuando cliquea la pagina del medio
function clickMedio(){
  //Desactivo pagina actual
  document.getElementsByClassName("page-item active")[0].className = "page-item";

  //Guardo pagina requerida
  var pagina = this.innerHTML;

  //Actualizo indice segun corresponda
  $("#anterior")[0].className = "page-item";
  $("#medio")[0].className = "page-item active";

  //Cosulta para cargar la pagina requerida
  $.ajax({
    url : '?action=cargarPaginaPacientes',
    data : { tipoBusqueda : tipoBusqueda, nombre : nombre, apellido : apellido, pagina : pagina,
             tipoDoc : tipoDoc, nroDoc : nroDoc, nroHistoriaClinica : nroHistoriaClinica },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaPacientes")[0].innerHTML = '<td align="center" colspan="7">No hay pacientes para mostrar</td>';
          $("#final")[0].className = "page-item disabled";
          $("#siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#cuerpoTablaPacientes")[0].innerHTML = respuesta.contenido;
          $("#final")[0].className = "page-item";
          $("#siguiente")[0].className = "page-item";
          asignarFuncionesALasOperaciones();
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
          $("#cuerpoTablaPacientes")[0].innerHTML = '<td align="center" colspan="7">No se pudo realizar la operacion solicitada</td>';
      }
    }
  });
}

//Se ejecuta cuando cliquea la pagina derecha
function clickFinal(){
  //Desactivo pagina actual
  document.getElementsByClassName("page-item active")[0].className = "page-item";

  //Guardo pagina requerida
  var pagina = this.innerHTML;

  //Actualizo indice segun corresponda
  $("#medio")[0].className = "page-item active";

  $("#btnInicio")[0].innerHTML = parseInt($("#btnInicio")[0].innerHTML) + 1;
  $("#btnMedio")[0].innerHTML = parseInt($("#btnMedio")[0].innerHTML) + 1;
  this.innerHTML = parseInt(this.innerHTML) + 1;

  $("#anterior")[0].className = "page-item"

  //Cosulta para cargar la pagina requerida
  $.ajax({
    url : '?action=cargarPaginaPacientes',
    data : { tipoBusqueda : tipoBusqueda, nombre : nombre, apellido : apellido, pagina : pagina,
             tipoDoc : tipoDoc, nroDoc : nroDoc, nroHistoriaClinica : nroHistoriaClinica },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaPacientes")[0].innerHTML = '<td align="center" colspan="7">No hay pacientes para mostrar</td>';
          $("#final")[0].className = "page-item disabled";
          $("#siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#cuerpoTablaPacientes")[0].innerHTML = respuesta.contenido;
          asignarFuncionesALasOperaciones();
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
          $("#cuerpoTablaPacientes")[0].innerHTML = '<td align="center" colspan="7">No se pudo realizar la operacion solicitada</td>';
      }
    }
  });
}

//Se ejecuta cuando se cliquea el boton para volver a la pag anterior
function anterior(){
  //Delega a la pag izquierda
  $("#btnInicio")[0].onclick()
}

//Se ejecuta cuando se cliquea el boton para pasar a la pag siguiente
function siguiente(){
  //Delega a la pag que corresponda
  if (document.getElementsByClassName("page-item active")[0].id == "inicio") {
    $("#btnMedio")[0].onclick()
  }else {
    $("#btnFinal")[0].onclick()
  }
}
//------------------ Operaciones sobre los pacientes ------------------
//########## Eliminar paciente ##########
function mostrarMensajeEliminacion(){
  $("#tituloMensaje").html("Eliminar paciente");
  $("#cuerpoMensaje").html("¿Esta seguro de que desea eliminar este paciente?")

  $("#botonMensaje")[0].paciente = this.parentNode.id;
  $("#botonMensaje")[0].onclick = eliminarPaciente;

  $("#mensajeConfirmacion").modal();
}

function eliminarPaciente(){
  var id = this.paciente;
  $.ajax({
    url : '?action=eliminarPaciente',
    data : { id: id },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si se realizo la operacion correctamente
      switch (respuesta.estado) {
        case "success":
          mostrarAlerta(respuesta.mensaje,respuesta.estado);
          document.getElementsByClassName("page-item active")[0].children[0].onclick();
          break;
        case "error":
          mostrarAlerta(respuesta.mensaje,respuesta.estado);
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
      }
    }
  });
}
//########## Agregar paciente ##########
function mostrarFormAlta() {
  if (this.value == "simple") {
    $("#form_completo").css({"display" : "none"});
    $("#form_simple").css({"display" : "block"});
    $("#btnAgregarPaciente")[0].onclick = agregarPacienteSimple;
  }else {
    $("#form_simple").css({"display" : "none"});
    $("#form_completo").css({"display" : "block"});
    $("#btnAgregarPaciente")[0].onclick = agregarPacienteCompleto;
  }
}

function cargarPartidos() {
  $.ajax({
    url : 'https://api-referencias.proyecto2018.linti.unlp.edu.ar/partido',
    data : {},
    type : 'GET',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(partidos) {
      //Pregunto si se realizo la operacion correctamente
      var select = $("#ac_partido");
      select.append($('<option/>').attr({ 'value': "" }).text('Seleccionar...'));

      for (var i = 0; i < partidos.length; i++) {
        var option = $('<option/>')[0];
        option.value = partidos[i].id;
        option.regionSanitaria = partidos[i].region_sanitaria_id;
        option.innerHTML = partidos[i].nombre
        select.append(option);
      }
    }
  });
}

function cargarRegionSanitaria() {
  var id = $("#ac_partido").find(":selected")[0].regionSanitaria;
  if (id) {
    $.ajax({
      url : 'https://api-referencias.proyecto2018.linti.unlp.edu.ar/region-sanitaria/' + id,
      data : {},
      type : 'GET',
      dataType: 'json',
      // código a ejecutar si la petición es satisfactoria;
      // la respuesta es pasada como argumento a la función
      success : function(regionSanitaria) {
        //Pregunto si se realizo la operacion correctamente
        $("#ac_regionSanitaria").val(regionSanitaria.nombre);
        $("#ac_regionSanitaria")[0].reg = regionSanitaria.id;
      }
    });
  }else {
    $("#ac_regionSanitaria").val("");
  }
}

function cargarLocalidades() {
  if (this.value) {
    $.ajax({
      url : 'https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad/partido/' + this.value,
      data : {},
      type : 'GET',
      dataType: 'json',
      // código a ejecutar si la petición es satisfactoria;
      // la respuesta es pasada como argumento a la función
      success : function(localidades) {
        //Pregunto si se realizo la operacion correctamente
        var select = $("#ac_localidad");
        select.html("");

        select.append($('<option/>').attr({ 'value': "" }).text('Seleccionar...'));

        for (var i = 0; i < localidades.length; i++) {
          var option = $('<option/>')[0];
          option.value = localidades[i].id;
          option.innerHTML = localidades[i].nombre
          select.append(option);
        }
      }
    });
  }else {
    $("#ac_localidad").html("");
  }
}

function cargarObrasSociales() {
  $.ajax({
    url : 'https://api-referencias.proyecto2018.linti.unlp.edu.ar/obra-social',
    data : {},
    type : 'GET',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(obrasSociales) {
      //Pregunto si se realizo la operacion correctamente
      var select = $("#ac_obraSocial");

      select.append($('<option/>').attr({ 'value': "" }).text('Seleccionar...'));

      for (var i = 0; i < obrasSociales.length; i++) {
        var option = $('<option/>')[0];
        option.value = obrasSociales[i].id;
        option.innerHTML = obrasSociales[i].nombre
        select.append(option);
      }
    }
  });
}

function cargarTiposDocumentos() {
  $.ajax({
    url : 'https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento',
    data : {},
    type : 'GET',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(tiposDocumentos) {
      //Pregunto si se realizo la operacion correctamente
      var select = $("#ac_tipoDoc");

      for (var i = 0; i < tiposDocumentos.length; i++) {
        var option = $('<option/>')[0];
        option.value = tiposDocumentos[i].id;
        option.innerHTML = tiposDocumentos[i].nombre
        select.append(option);
      }
    }
  });
}

function agregarPacienteSimple() {
  var nroHC = $("#as_nroHC").val();

  $.ajax({
    url : '?action=agregarPacienteSimple',
    data : {nroHC: nroHC},
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si se realizo la operacion correctamente
      switch (respuesta.estado) {
        case "success":
          mostrarAlerta(respuesta.mensaje,respuesta.estado);
          $('#formularioAgregarUsuario').trigger("reset");
          document.getElementsByClassName("page-item active")[0].children[0].onclick();
          break;
        case "error":
          mostrarAlerta(respuesta.mensaje,respuesta.estado);
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
      }
    }
  });
}

function agregarPacienteCompleto() {
  var nombre = $("#ac_nombre").val();
  var apellido = $("#ac_apellido").val();
  var lNacimiento = $("#ac_lNacimiento").val();
  var fNacimiento = $("#ac_fNacimiento").val();
  var partido = $("#ac_partido").val();
  var localidad = $("#ac_localidad").val();
  var domicilio = $("#ac_domicilio").val();
  var genero = $("#ac_genero").val();
  var tieneDoc = $("#ac_tieneDoc").val();
  var tipoDoc = $("#ac_tipoDoc").val();
  var nroDoc = $("#ac_nroDoc").val();
  var nroHC = $("#ac_nroHC").val();
  var nroCarpeta = $("#ac_nroCarpeta").val();
  var nroTel_cel = $("#ac_nroTel_cel").val();
  var obraSocial = $("#ac_obraSocial").val();
  var regionSanitaria = $("#ac_regionSanitaria")[0].identificador;

  $.ajax({
    url : '?action=agregarPacienteCompleto',
    data : { nombre: nombre, apellido: apellido, lNacimiento: lNacimiento, fNacimiento: fNacimiento,
      partido: partido, localidad: localidad, domicilio: domicilio, genero: genero,
      tieneDoc: tieneDoc, tipoDoc: tipoDoc, nroDoc: nroDoc, nroHC: nroHC,
      nroCarpeta: nroCarpeta, nroTel_cel: nroTel_cel, obraSocial: obraSocial, regionSanitaria: regionSanitaria },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si se realizo la operacion correctamente
      switch (respuesta.estado) {
        case "success":
          mostrarAlerta(respuesta.mensaje,respuesta.estado);
          $('#formularioAgregarUsuario').trigger("reset");
          document.getElementsByClassName("page-item active")[0].children[0].onclick();
          break;
        case "error":
          mostrarAlerta(respuesta.mensaje,respuesta.estado);
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
      }
    }
  });
}
//########## Ver detalle paciente ##########
function mostrarDetalle() {
  var id = this.parentNode.id;
  $.ajax({
    url : '?action=detallePaciente',
    data : { id: id },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      switch (respuesta.estado) {
        case "success":
          $("#contenidoVerPaciente").html(respuesta.contenido);
          $("#tabVerPaciente").css({"display":"block"});
          $('#menuTabs a[href="#contenidoVerPaciente"]').tab('show');
          break;
        case "error":
          mostrarAlerta(respuesta.mensaje, respuesta.estado);
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion vuelva a intentar mas tarde","error");
      }
    }
  });
}

//------------------ Inicializar ------------------
//Pregunto si tiene dicha funcionalidad
if ($("#contenidoModificarPaciente")[0]) {
  // Para quitar el formulario para modificar el paciente cuando se clickee la pestaña "Pacientes"
  $('#menuTabs a[href="#contenidoPacientes"]').bind('click', function (e) {
    e.preventDefault()
    console.log("sadasd");
    $("#tabModificarPaciente").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $("#contenidoModificarPaciente").html("...");
    }, 250);
  })

  // Para quitar el formulario para modificar el paciente cuando se clickee la pestaña "Agregar"
  $('#menuTabs a[href="#contenidoAgregarPaciente"]').bind('click', function (e) {
    e.preventDefault()
    $("#tabModificarPaciente").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $("#contenidoModificarPaciente").html("...");
    }, 250);
  })
}

//Pregunto si tiene dicha funcionalidad
if ($("#contenidoVerPaciente")[0]) {
  // Para quitar el detalle del paciente clickee la pestaña "Pacientes"
  $('#menuTabs a[href="#contenidoPacientes"]').bind('click', function (e) {
    e.preventDefault()
    $("#tabVerPaciente").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $("#contenidoVerPaciente").html("...");
    }, 250);
  })

  // Para quitar el formulario para modificar el paciente cuando se clickee la pestaña "Agregar"
  $('#menuTabs a[href="#contenidoAgregarPaciente"]').bind('click', function (e) {
    e.preventDefault()
    $("#tabVerPaciente").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $("#contenidoVerPaciente").html("...");
    }, 250);
  })
}

//Asigna las funciones a los botones de las operaciones
function asignarFuncionesALasOperaciones(){
  document.getElementsByName("eliminar").forEach(function(btnEliminar){
    btnEliminar.onclick = mostrarMensajeEliminacion;
  })

  document.getElementsByName("ver_detalle").forEach(function(btnVer){
    btnVer.onclick = mostrarDetalle;
  })

  document.getElementsByName("modificar").forEach(function(btnModificar){
    btnModificar.onclick = "mostrarFormularioModificacion";
  })

}

//Asigno valores y funciones a los botones y campos.
function initialize(){
  //pregunto por modulo de alta
  if ($("#tabAgregarPaciente")[0]) {
    $("#btnAgregarPaciente")[0].onclick = agregarPacienteSimple;
    $("#tipoDeAlta")[0].value = "simple";
    $("#tipoDeAlta")[0].onchange = mostrarFormAlta;

    //Select del formulario paciente form_completo
    cargarPartidos();
    cargarObrasSociales();
    cargarTiposDocumentos();
    $("#ac_partido").bind('change', cargarRegionSanitaria);
    $("#ac_partido").bind('change', cargarLocalidades);
  }

  if ($("#tabPacientes")[0]) {
    $("#btnAnterior")[0].onclick = anterior;
    $("#btnInicio")[0].onclick = clickInicio;
    $("#btnMedio")[0].onclick = clickMedio;
    $("#btnFinal")[0].onclick = clickFinal;
    $("#btnSiguiente")[0].onclick = siguiente;

    $("#btnBuscar")[0].onclick = buscar;
    $("#tipoBusqueda")[0].value = "no_aplica";
    $("#tipoBusqueda")[0].onchange = mostrarFormBusqueda;
    $("#bus_nombre")[0].value = "";
    $("#bus_apellido")[0].value = "";
    $("#bus_tipoDoc")[0].value = "";
    $("#bus_nroDoc")[0].value = "";
    $("#bus_nroHistoriaClinica")[0].value = "";
    //Disparo para cargar la pagina inicial
    $("#btnInicio")[0].onclick();
  }else {
    $('#menuTabs a[href="#contenidoAgregarPaciente"]').click();
  }
}

//Disparo para levantar el sistema de funciones.
initialize();
