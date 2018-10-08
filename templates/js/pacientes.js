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

//Se ejecuta cuando se busca por algun campo
function buscar(){
  //Actualizo criterios de busqueda
  tipoBusqueda = $("#tipoBusqueda")[0].value;
  nombre = $("#bus_nombre")[0].value;
  apellido = $("#bus_apellido")[0].value;
  tipoDoc = $("#bus_nroDoc")[0].value;
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
        case "incompleto":
          mostrarAlerta("Campos incompletos","error");
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
        case "incompleto":
          mostrarAlerta("Campos incompletos","error");
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
        case "incompleto":
          mostrarAlerta("Campos incompletos","error");
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

//------------------ Inicializar ------------------
//Pregunto si tiene dicha funcionalidad
if ($("#contenidoModificarPaciente")[0]) {

  // Para quitar el formulario para modificar el usuario cuando se clickee la pestaña "Usuarios"
  $('#menuTabs a[href="#contenidoPacientes"]').on('click', function (e) {
    e.preventDefault()
    $("#tabModificarPaciente").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $("#contenidoModificarPaciente").html("...");
    }, 250);
  })

  // Para quitar el formulario para modificar el usuario cuando se clickee la pestaña "Agregar"
  $('#menuTabs a[href="#contenidoAgregarPaciente"]').on('click', function (e) {
    e.preventDefault()
    $("#tabModificarPaciente").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $("#contenidoModificarPaciente").html("...");
    }, 250);
  })
}

//Asigna las funciones a los botones de las operaciones
function asignarFuncionesALasOperaciones(){
  document.getElementsByName("bloquear").forEach(function(btnBloquear){
    btnBloquear.onclick= mostrarMensajeBloqueo;
  })

  document.getElementsByName("activar").forEach(function(btnActivar){
    btnActivar.onclick= mostrarMensajeActivacion;
  })

  document.getElementsByName("modificar").forEach(function(btnModificar){
    btnModificar.onclick= mostrarFormularioModificacion;
  })

  document.getElementsByName("administrar-rol").forEach(function(btnAdministrar){
    btnAdministrar.onclick= mostrarPanelAdministracionRoles;
  })

}

//Asigno valores y funciones a los botones y campos.
function initialize(){
  if ($("#btnAgregarPaciente")[0]) {
    $("#btnAgregarPaciente")[0].onclick = agregarPaciente;
  }

  if ($("#tabPacientes")[0]) {
    $("#btnAnterior")[0].onclick = anterior;
    $("#btnInicio")[0].onclick = clickInicio;
    $("#btnMedio")[0].onclick = clickMedio;
    $("#btnFinal")[0].onclick = clickFinal;
    $("#btnSiguiente")[0].onclick = siguiente;

    $("#btnBuscar")[0].onclick = buscar;
    $("#tipoBusqueda")[0].value = "no aplica";
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
