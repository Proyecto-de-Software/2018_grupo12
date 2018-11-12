if ($("#tabConsultas")[0]) {

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
/*
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
*/
//Se ejecuta cuando se clickea la pagina izquierda
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
    $("#anterior")[0].className = "page-item disabled";
    $("#inicio")[0].className = "page-item active";
  }

  //Cosulta para cargar la pagina requerida
  $.ajax({
    url : '?action=cargarPaginaConsultas',
    data : { pagina : pagina },
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
          $("#cuerpoTablaPacientes")[0].innerHTML = '<tr><td class="textcenter" colspan="6">No se pudo realizar la operacion solicitada</td></tr>';
      }
    }
  });
}

//Se ejecuta cuando clickea la pagina del medio
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
    url : '?action=cargarPaginaConsultas',
    data : { pagina : pagina },
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
          $("#cuerpoTablaPacientes")[0].innerHTML = '<tr><td class="textcenter" colspan="6">No se pudo realizar la operacion solicitada</td></tr>';
      }
    }
  });
}

//Se ejecuta cuando clickea la pagina derecha
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
    url : '?action=cargarPaginaConsultas',
    data : { pagina : pagina },
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
  if (document.getElementsByClassName("page-item active")[0].id == "inicio") {
    $("#btnMedio")[0].onclick()
  }else {
    $("#btnFinal")[0].onclick()
  }
}
//------------------ Baja de consultas ------------------
function mostrarMensajeEliminacion(){
  $("#tituloMensaje").html("Eliminar consulta");
  $("#cuerpoMensaje").html("¿Esta seguro de que desea eliminar esta consulta?")

  $("#botonMensaje")[0].consulta = this.parentNode.id;
  $("#botonMensaje")[0].onclick = eliminarConsulta;

  $("#mensajeConfirmacion").modal();
}

function eliminarConsulta(){
  var id = this.consulta;
  $.ajax({
    url : '?action=eliminarConsulta',
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
//------------------ Carga de Formularios alta, modificacion y busqueda ------------------

//Recibo el id del select a cargar
function cargarInstituciones() {
  $.ajax({
    url : 'api instituciones!!',
    data : {},
    type : 'GET',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(instituciones) {
      //Pregunto si se realizo la operacion correctamente
      var select = $("idSelectPartido");
      select.append($('<option/>').attr({ 'value': "" }).text('Seleccionar...'));

      for (var i = 0; i < partidos.length; i++) {
        var option = $('<option/>')[0];
        option.value = instituciones[i].id;
        option.regionSanitaria = partidos[i].region_sanitaria_id;
        option.innerHTML = partidos[i].nombre;
        select.append(option);
      }
    }
  });
}

//------------------ Alta de consultas ------------------

//Funcion para agregar paciente con todos sus datos
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
  var regionSanitaria = $("#ac_regionSanitaria")[0].reg;

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
          $('#formularioAgregarPaciente').trigger("reset");
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
  $.ajax({
    url : '?action=formularioModificacionPaciente',
    data : { id: id },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(paciente) {
      if (paciente.estado == "success") {
        $("#m_nombre").val(paciente.nombre);
        $("#m_apellido").val(paciente.apellido);
        $("#m_fNacimiento").val(paciente.fecha_nac);
        $("#m_lNacimiento").val(paciente.lugar_nac);
        $("#m_partido").val(paciente.partido_id);
        $("#m_domicilio").val(paciente.domicilio);
        $("#m_genero").val(paciente.genero_id);
        $("#m_tieneDoc").val(paciente.tiene_documento);
        $("#m_tipoDoc").val(paciente.tipo_doc_id);
        $("#m_nroDoc").val(paciente.numero);
        $("#m_nroHC").val(paciente.nro_historia_clinica);
        $("#m_nroCarpeta").val(paciente.nro_carpeta);
        $("#m_nroTel_cel").val(paciente.tel);
        $("#m_obraSocial").val(paciente.obra_social_id);
        cargarLocalidades("#m_partido","#m_localidad",paciente.localidad_id);
        cargarRegionSanitaria("#m_partido", "#m_regionSanitaria");

        $("#btnModificarPaciente")[0].paciente = id;
        $("#btnModificarPaciente")[0].onclick = modificarPaciente;
        $("#tabModificarPaciente").css({"display":"block"});
        $('#menuTabs a[href="#contenidoModificarPaciente"]').tab('show');
      }else {
        mostrarAlerta("No se pudo realizar la operacion vuelva a intentar mas tarde","error");
      }
    }
  });
}

function modificarPaciente(){
  //id del paciente a modificar
  var id = this.paciente;

  //Tomo datos del formulario
  var nombre = $("#m_nombre").val();
  var apellido = $("#m_apellido").val();
  var lNacimiento = $("#m_lNacimiento").val();
  var fNacimiento = $("#m_fNacimiento").val();
  var partido = $("#m_partido").val();
  var localidad = $("#m_localidad").val();
  var domicilio = $("#m_domicilio").val();
  var genero = $("#m_genero").val();
  var tieneDoc = $("#m_tieneDoc").val();
  var tipoDoc = $("#m_tipoDoc").val();
  var nroDoc = $("#m_nroDoc").val();
  var nroHC = $("#m_nroHC").val();
  var nroCarpeta = $("#m_nroCarpeta").val();
  var nroTel_cel = $("#m_nroTel_cel").val();
  var obraSocial = $("#m_obraSocial").val();
  var regionSanitaria = $("#m_regionSanitaria")[0].reg;

  $.ajax({
    url : '?action=modificarPaciente',
    data : { id:id, nombre: nombre, apellido: apellido, lNacimiento: lNacimiento, fNacimiento: fNacimiento,
      partido: partido, localidad: localidad, domicilio: domicilio, genero: genero,
      tieneDoc: tieneDoc, tipoDoc: tipoDoc, nroDoc: nroDoc, nroHC: nroHC,
      nroCarpeta: nroCarpeta, nroTel_cel: nroTel_cel, obraSocial: obraSocial, regionSanitaria: regionSanitaria },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Tengo en cuenta los posibles casos
      switch(respuesta.estado) {
        case "success":
          mostrarAlerta(respuesta.mensaje, respuesta.estado);
          document.getElementsByClassName("page-item active")[0].children[0].onclick();
          $('#menuTabs a[href="#contenidoPacientes"]').click();
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
  $.ajax({
    url : '?action=detalleConsulta',
    data : { id: id },
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
//Pregunto si tiene dicha funcionalidad
if ($("#contenidoModificarPaciente")[0]) {
  // Para quitar el formulario para modificar el paciente cuando se clickee la pestaña "Pacientes"
  $('#menuTabs a[href="#contenidoPacientes"]').bind('click', function (e) {
    e.preventDefault()
    $("#tabModificarPaciente").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $('#formularioModificarPaciente').trigger("reset");
      $('#btnModificarPaciente')[0].onclick = "";
      $("#btnModificarPaciente")[0].paciente = "";
    }, 250);
  })

  // Para quitar el formulario para modificar el paciente cuando se clickee la pestaña "Agregar"
  $('#menuTabs a[href="#contenidoAgregarPaciente"]').bind('click', function (e) {
    e.preventDefault()
    $("#tabModificarPaciente").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $('#formularioModificarPaciente').trigger("reset");
      $('#btnModificarPaciente')[0].onclick = "";
      $("#btnModificarPaciente")[0].paciente = "";
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
    btnModificar.onclick = mostrarFormularioModificacion;
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
    cargarTiposDocumentos("#ac_tipoDoc");
    cargarPartidos("#ac_partido");
    cargarObrasSociales("#ac_obraSocial");
    $("#ac_partido").bind('change', function(){ cargarRegionSanitaria("#ac_partido", "#ac_regionSanitaria") });
    $("#ac_partido").bind('change', function(){ cargarLocalidades("#ac_partido", "#ac_localidad") });
    $("#ac_regionSanitaria")[0].reg = "";
  }

  //pregunto por modulo de Modificacion
  if ($("#tabModificarPaciente")[0]) {
    cargarTiposDocumentos("#m_tipoDoc");
    cargarPartidos("#m_partido");
    cargarObrasSociales("#m_obraSocial");
    $("#m_partido").bind('change', function(){ cargarRegionSanitaria("#m_partido", "#m_regionSanitaria") });
    $("#m_partido").bind('change', function(){ cargarLocalidades("#m_partido", "#m_localidad") });
    $("#m_regionSanitaria")[0].reg = "";
  }

  //Pregunto por modulo de listado (contiene baja y show)
  if ($("#tabConsultas")[0]) {
    $("#btnAnterior")[0].onclick = anterior;
    $("#btnInicio")[0].onclick = clickInicio;
    $("#btnMedio")[0].onclick = clickMedio;
    $("#btnFinal")[0].onclick = clickFinal;
    $("#btnSiguiente")[0].onclick = siguiente;

    /*
    $("#btnBuscar")[0].onclick = buscar;
    $("#tipoBusqueda")[0].value = "no_aplica";
    $("#tipoBusqueda")[0].onchange = mostrarFormBusqueda;
    $("#bus_nombre")[0].value = "";
    $("#bus_apellido")[0].value = "";
    $("#bus_tipoDoc")[0].value = "";
    $("#bus_nroDoc")[0].value = "";
    $("#bus_nroHistoriaClinica")[0].value = "";
    */
    //Disparo para cargar la pagina inicial
    $("#btnInicio")[0].onclick();
  }else {
    $('#menuTabs a[href="#contenidoAgregarPaciente"]').click();
  }
}

//Disparo para levantar el sistema de funciones.
initialize();
