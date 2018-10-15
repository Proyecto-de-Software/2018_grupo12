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

function agregarPacienteSimple() {

}

function agregarPacienteCompleto() {

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
  if ($("#btnAgregarPaciente")[0]) {
    $("#btnAgregarPaciente")[0].onclick = agregarPacienteSimple;
    $("#tipoDeAlta")[0].value = "simple";
    $("#tipoDeAlta")[0].onchange = mostrarFormAlta;
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
