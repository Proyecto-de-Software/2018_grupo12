if ($("#tabConsultas")[0]) {
  var tipoBusqueda = document.getElementById("opcionPorDefecto").value;
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
  tipoDoc = $("#bus_tipoDoc")[0].value;
  nroDoc = $("#bus_nroDoc")[0].value;
  nroHistoriaClinica = $("#bus_nroHistoriaClinica")[0].value;


  //Desactivo pagina actual
  $('#navPags').find("[class='page-item active']")[0].className = "page-item";

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

//Se ejecuta cuando se clickea la pagina izquierda
function clickInicio(){
  //Desactivo pagina actual
  $('#navPags').find("[class='page-item active']")[0].className = "page-item";

  //Guardo pagina requerida
  var pagina = this.innerHTML;
  var token = $('meta[name="token"]').attr('content');

  //Actualizo indice segun corresponda
  if (pagina != "1") {
    $("#medio")[0].className = "page-item active";

    this.innerHTML = parseInt(this.innerHTML) - 1;
    $("#btnMedio")[0].innerHTML = parseInt($("#btnMedio")[0].innerHTML) - 1;
    $("#btnFinal")[0].innerHTML = parseInt($("#btnFinal")[0].innerHTML) - 1;
  }else {
    $("#anterior")[0].className = "page-item disabled";
    $("#inicio")[0].className = "page-item active";
  }

  //Cosulta para cargar la pagina requerida
  $.ajax({
    url : '?action=cargarPaginaConsultas',
    data : { pagina: pagina, tipoBusqueda: tipoBusqueda, tipoDoc: tipoDoc,
             nroDoc: nroDoc, nroHistoriaClinica: nroHistoriaClinica, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaConsultas")[0].innerHTML = '<tr><td class="textcenter" colspan="6">No hay consultas para mostrar</td></tr>';
          if (pagina == "1") {
            $("#medio")[0].className = "page-item disabled";
          }
          $("#final")[0].className = "page-item disabled";
          $("#siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#cuerpoTablaConsultas")[0].innerHTML = respuesta.contenido;
          if (pagina == 1) {
            $("#medio")[0].className = "page-item";
          }
          $("#final")[0].className = "page-item";
          $("#siguiente")[0].className = "page-item";
          if (respuesta.pagRestantes <= 0) {
            if (pagina == 1) {
              $("#medio")[0].className = "page-item disabled";
            }
            $("#final")[0].className = "page-item disabled";
            $("#siguiente")[0].className = "page-item disabled";
          }else if (respuesta.pagRestantes == 1) {
            if (pagina == 1) {
              $("#final")[0].className = "page-item disabled";
            }
          }
          asignarFuncionesALasOperaciones();
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
          $("#cuerpoTablaConsultas")[0].innerHTML = '<tr><td class="textcenter" colspan="6">No se pudo realizar la operacion solicitada</td></tr>';
      }
    }
  });
}

//Se ejecuta cuando clickea la pagina del medio
function clickMedio(){
  //Desactivo pagina actual
  $('#navPags').find("[class='page-item active']")[0].className = "page-item";

  //Guardo pagina requerida
  var pagina = this.innerHTML;
  var token = $('meta[name="token"]').attr('content');

  //Actualizo indice segun corresponda
  $("#anterior")[0].className = "page-item";
  $("#medio")[0].className = "page-item active";

  //Cosulta para cargar la pagina requerida
  $.ajax({
    url : '?action=cargarPaginaConsultas',
    data : { pagina : pagina, tipoBusqueda: tipoBusqueda, tipoDoc: tipoDoc,
             nroDoc: nroDoc, nroHistoriaClinica: nroHistoriaClinica, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaConsultas")[0].innerHTML = '<tr><td class="textcenter" colspan="6">No hay consultas para mostrar</td></tr>';
          $("#final")[0].className = "page-item disabled";
          $("#siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#cuerpoTablaConsultas")[0].innerHTML = respuesta.contenido;
          $("#final")[0].className = "page-item";
          $("#siguiente")[0].className = "page-item";
          if (respuesta.pagRestantes <= 0) {
            $("#final")[0].className = "page-item disabled";
            $("#siguiente")[0].className = "page-item disabled";
          }
          asignarFuncionesALasOperaciones();
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
          $("#cuerpoTablaConsultas")[0].innerHTML = '<tr><td class="textcenter" colspan="6">No se pudo realizar la operacion solicitada</td></tr>';
      }
    }
  });
}

//Se ejecuta cuando clickea la pagina derecha
function clickFinal(){
  //Desactivo pagina actual
  $('#navPags').find("[class='page-item active']")[0].className = "page-item";

  //Guardo pagina requerida
  var pagina = this.innerHTML;
  var token = $('meta[name="token"]').attr('content');

  //Actualizo indice segun corresponda
  $("#medio")[0].className = "page-item active";

  $("#btnInicio")[0].innerHTML = parseInt($("#btnInicio")[0].innerHTML) + 1;
  $("#btnMedio")[0].innerHTML = parseInt($("#btnMedio")[0].innerHTML) + 1;
  this.innerHTML = parseInt(this.innerHTML) + 1;

  $("#anterior")[0].className = "page-item"

  //Cosulta para cargar la pagina requerida
  $.ajax({
    url : '?action=cargarPaginaConsultas',
    data : { pagina : pagina, tipoBusqueda: tipoBusqueda, tipoDoc: tipoDoc,
             nroDoc: nroDoc, nroHistoriaClinica: nroHistoriaClinica, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaConsultas")[0].innerHTML = '<tr><td class="textcenter" colspan="6">No hay consultas para mostrar</td><tr>';
          $("#final")[0].className = "page-item disabled";
          $("#siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#cuerpoTablaConsultas")[0].innerHTML = respuesta.contenido;
          $("#final")[0].className = "page-item";
          $("#siguiente")[0].className = "page-item";
          if (respuesta.pagRestantes <= 0) {
            $("#final")[0].className = "page-item disabled";
            $("#siguiente")[0].className = "page-item disabled";
          }
          asignarFuncionesALasOperaciones();
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
          $("#cuerpoTablaConsultas")[0].innerHTML = '<tr><td class="textcenter" colspan="6">No se pudo realizar la operacion solicitada</td></tr>';
      }
    }
  });
}

//Se ejecuta cuando se clickea el boton para volver a la pag anterior
function anterior(){
  //Delega a la pag izquierda
  $("#btnInicio")[0].onclick()
}

//Se ejecuta cuando se clickea el boton para pasar a la pag siguiente
function siguiente(){
  //Delega a la pag que corresponda
  if ($('#navPags').find("[class='page-item active']")[0].id == "inicio") {
    $("#btnMedio")[0].onclick()
  }else {
    $("#btnFinal")[0].onclick()
  }
}
//------------------ Carga de inputs de los formularios ------------------
//Recibo el id del select a cargar
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
      var select = $("#bus_tipoDoc");
      select.append($('<option/>').attr({ 'value': "" }).text('Seleccionar...'));

      for (var i = 0; i < tiposDocumentos.length; i++) {
        var option = $('<option/>')[0];
        option.value = tiposDocumentos[i].id;
        option.innerHTML = tiposDocumentos[i].nombre
        select.append(option);
      }
    }
  });
}
//------------------ Baja de consultas ------------------
function eliminarConsulta(){
  var id = this.consulta;
  var token = $('meta[name="token"]').attr('content');

  $.ajax({
    url : '?action=eliminarConsulta',
    data : { id: id, token: token },
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

function mostrarMensajeEliminacion(){
  $("#tituloMensaje").html("Eliminar consulta");
  $("#cuerpoMensaje").html("¿Esta seguro de que desea eliminar esta consulta?")

  $("#botonMensaje")[0].consulta = this.parentNode.id;
  $("#botonMensaje")[0].onclick = eliminarConsulta;

  $("#mensajeConfirmacion").modal();
}
//------------------ Carga de Formularios alta, modificacion y busqueda ------------------

//Recibo el id del select a cargar
function cargarInstituciones() {
  $.ajax({
    url : 'https://grupo12.proyecto2018.linti.unlp.edu.ar/apiRest/api.php/instituciones',
    data : {},
    type : 'GET',
    dataType: 'json',
    success : function(instituciones) {
      instituciones = instituciones.sort(function(a,b){ return (a.nombre > b.nombre)})
      var select = $("#a_derivacion");
      select.append($('<option/>').attr({ 'value': "" }).text('Seleccionar...'));

      for (var i = 0; i < instituciones.length; i++) {
        var option = $('<option/>')[0];
        option.value = instituciones[i].id;
        option.innerHTML = instituciones[i].nombre;
        select.append(option);
      }
    }
  });
}

//------------------ Alta de consultas ------------------

//Funcion para agregar paciente con todos sus datos
function agregarConsulta() {
  var id = this.value;
  var fecha = $("#a_fecha").val();
  var motivo = $("#a_motivo").val();
  var derivacion = $("#a_derivacion").val();
  var internacion = $("#a_internacion").val();
  var tratamiento = $("#a_tratamiento").val();
  var acompanamiento = $("#a_acompanamiento").val();
  var articulacion = $("#a_articulacion").val();
  var diagnostico = $("#a_diagnostico").val();
  var observaciones = $("#a_observaciones").val();
  var token = $('meta[name="token"]').attr('content');

  $.ajax({
    url : '?action=agregarConsulta',
    data : { id: id, fecha: fecha, motivo: motivo, derivacion: derivacion,
      internacion: internacion, tratamiento: tratamiento, acompanamiento: acompanamiento,
      articulacion: articulacion, diagnostico: diagnostico, observaciones: observaciones, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si se realizo la operacion correctamente
      switch (respuesta.estado) {
        case "success":
          adminMarkers.clearMarkers();
          mostrarAlerta(respuesta.mensaje,respuesta.estado);
          $('#formularioAgregarConsulta').trigger("reset");
          $("#btnAgregarConsulta").val("");
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
//------------------ Moficacion de pacientes ------------------
function mostrarFormularioModificacion(){
  var id = this.parentNode.id;
  var token = $('meta[name="token"]').attr('content');

  $.ajax({
    url : '?action=formularioModificacionConsulta',
    data : { id: id, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(consulta) {
      if (consulta.estado == "success") {
        $("#m_tratamiento").val(consulta.tratamiento);
        $("#m_articulacion").val(consulta.articulacion);
        $("#m_diagnostico").val(consulta.diagnostico);
        $("#m_observaciones").val(consulta.observaciones);

        $("#btnModificarConsulta")[0].consulta = id;
        $("#btnModificarConsulta")[0].onclick = modificarConsulta;
        $("#tabModificarConsulta").css({"display":"block"});
        $('#menuTabs a[href="#contenidoModificarConsulta"]').tab('show');
      }else {
        mostrarAlerta("No se pudo realizar la operacion vuelva a intentar mas tarde","error");
      }
    }
  });
}

function modificarConsulta(){
  //id del paciente a modificar
  var id = this.consulta;

  //Tomo datos del formulario
  var tratamiento = $("#m_tratamiento").val();
  var articulacion = $("#m_articulacion").val();
  var diagnostico = $("#m_diagnostico").val();
  var observaciones = $("#m_observaciones").val();
  var token = $('meta[name="token"]').attr('content');

  $.ajax({
    url : '?action=modificarConsulta',
    data : { id:id, tratamiento: tratamiento, articulacion: articulacion,
             diagnostico: diagnostico, observaciones: observaciones, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Tengo en cuenta los posibles casos
      switch(respuesta.estado) {
        case "success":
          $('#formularioModificarConsulta').trigger("reset");
          $("#btnModificarConsulta")[0].consulta = "";
          $("#btnModificarConsulta")[0].onclick = "";
          mostrarAlerta(respuesta.mensaje, respuesta.estado);
          document.getElementsByClassName("page-item active")[0].children[0].onclick();
          $('#menuTabs a[href="#contenidoConsultas"]').click();
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

//------------------ Ver informacion completa de pacientes ------------------
function mostrarDetalle() {
  var id = this.parentNode.id;
  var token = $('meta[name="token"]').attr('content');

  $.ajax({
    url : '?action=detalleConsulta',
    data : { id: id, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      switch (respuesta.estado) {
        case "success":
          $("#contenidoVerConsulta").html(respuesta.contenido);
          $("#tabVerConsulta").css({"display":"block"});
          $('#menuTabs a[href="#contenidoVerConsulta"]').tab('show');
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
//Borrar datos del mensaje cuando se oculte
$('#mensajeConfirmacion').on('hidden.bs.modal', function (e) {
  $("#botonMensaje")[0].consulta = "";
  $("#botonMensaje")[0].onclick = "";
})
//Pregunto si tiene dicha funcionalidad
if ($("#contenidoModificarConsulta")[0]) {
  // Para quitar el formulario para modificar la consulta cuando se clickee la pestaña "Consultas"
  $('#menuTabs a[href="#contenidoConsultas"]').bind('click', function (e) {
    e.preventDefault()
    $("#tabModificarConsulta").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $('#formularioModificarConsulta').trigger("reset");
      $('#btnModificarConsulta')[0].onclick = "";
      $("#btnModificarConsulta")[0].consulta = "";
    }, 250);
  })

  // Para quitar el formulario para modificar la consulta cuando se clickee la pestaña "Agregar consulta"
  $('#menuTabs a[href="#contenidoAgregarConsulta"]').bind('click', function (e) {
    e.preventDefault()
    $("#tabModificarConstula").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $('#formularioModificarConsulta').trigger("reset");
      $('#btnModificarConsulta')[0].onclick = "";
      $("#btnModificarConsulta")[0].paciente = "";
    }, 250);
  })
}

//Pregunto si tiene dicha funcionalidad
if ($("#contenidoVerConsulta")[0]) {
  // Para quitar el detalle de la consulta clickee la pestaña "Consultas"
  $('#menuTabs a[href="#contenidoConsultas"]').bind('click', function (e) {
    e.preventDefault()
    $("#tabVerConsulta").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $("#contenidoVerConsulta").html("...");
    }, 250);
  })

  // Para quitar el formulario para modificar la consulta cuando se clickee la pestaña "Agregar consulta"
  $('#menuTabs a[href="#contenidoAgregarConsulta"]').bind('click', function (e) {
    e.preventDefault()
    $("#tabVerConsulta").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $("#contenidoVerConsulta").html("...");
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
    btnModificar.onclick = mostrarFormularioModificacion;
  })

}

//Asigno valores y funciones a los botones y campos.
function initialize(){

  //pregunto por modulo de alta
  if ($("#tabAgregarConsulta")[0]) {
    $("#btnAgregarConsulta")[0].onclick = agregarConsulta;

    //Select a_derivacion del formulario de alta de una consulta
    cargarInstituciones();
  }

  //pregunto por modulo de Modificacion
  if ($("#tabModificarPaciente")[0]) {
    $("#m_partido").bind('change', function(){ cargarRegionSanitaria("#m_partido", "#m_regionSanitaria") });
    $("#m_partido").bind('change', function(){ cargarLocalidades("#m_partido", "#m_localidad") });
    $("#m_regionSanitaria")[0].reg = "";
  }

  //Pregunto por modulo de listado (contiene baja y show)
  if ($("#tabConsultas")[0]) {
    cargarTiposDocumentos();

    $("#btnAnterior")[0].onclick = anterior;
    $("#btnInicio")[0].onclick = clickInicio;
    $("#btnMedio")[0].onclick = clickMedio;
    $("#btnFinal")[0].onclick = clickFinal;
    $("#btnSiguiente")[0].onclick = siguiente;


    $("#btnBuscar")[0].onclick = buscar;
    $("#tipoBusqueda")[0].value = "no_aplica";
    $("#tipoBusqueda")[0].onchange = mostrarFormBusqueda;
    $("#bus_tipoDoc")[0].value = "";
    $("#bus_nroDoc")[0].value = "";
    $("#bus_nroHistoriaClinica")[0].value = "";

    //Disparo para cargar la pagina inicial
    $("#btnInicio")[0].onclick();
  }else {
    $('#menuTabs a[href="#contenidoAgregarConsulta"]').click();
  }
}

//Disparo para levantar el sistema de funciones.
initialize();
