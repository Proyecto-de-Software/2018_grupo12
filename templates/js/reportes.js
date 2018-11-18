var agrupacion = document.getElementById("opcionPorDefecto").value;

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
//Se ejecuta cuando se cambia el select
function buscar(){
  //Actualizo criterios de busqueda
  agrupacion = $("#agrupar")[0].value;


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

//Se ejecuta cuando se clickea la pagina izquierda
function clickInicio(){
  //Desactivo pagina actual
  document.getElementsByClassName("page-item active")[0].className = "page-item";

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
    url : '?action=cargarPaginaReporte',
    data : { pagina: pagina, agrupacion: agrupacion, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaConsultas")[0].innerHTML = '<tr><td class="textcenter" colspan="7">No hay consultas para mostrar</td></tr>';
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
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
          $("#cuerpoTablaPacientes")[0].innerHTML = '<tr><td class="textcenter" colspan="7">No se pudo realizar la operacion solicitada</td></tr>';
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
  var token = $('meta[name="token"]').attr('content');

  //Actualizo indice segun corresponda
  $("#anterior")[0].className = "page-item";
  $("#medio")[0].className = "page-item active";

  //Cosulta para cargar la pagina requerida
  $.ajax({
    url : '?action=cargarPaginaReporte',
    data : { pagina: pagina, agrupacion: agrupacion, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaConsultas")[0].innerHTML = '<tr><td class="textcenter" colspan="7">No hay consultas para mostrar</td></tr>';
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
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
          $("#cuerpoTablaPacientes")[0].innerHTML = '<tr><td class="textcenter" colspan="7">No se pudo realizar la operacion solicitada</td></tr>';
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
  var token = $('meta[name="token"]').attr('content');

  //Actualizo indice segun corresponda
  $("#medio")[0].className = "page-item active";

  $("#btnInicio")[0].innerHTML = parseInt($("#btnInicio")[0].innerHTML) + 1;
  $("#btnMedio")[0].innerHTML = parseInt($("#btnMedio")[0].innerHTML) + 1;
  this.innerHTML = parseInt(this.innerHTML) + 1;

  $("#anterior")[0].className = "page-item"

  //Cosulta para cargar la pagina requerida
  $.ajax({
    url : '?action=cargarPaginaReporte',
    data : { pagina: pagina, agrupacion: agrupacion, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaConsultas")[0].innerHTML = '<tr><td class="textcenter" colspan="7">No hay consultas para mostrar</td><tr>';
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
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
          $("#cuerpoTablaConsultas")[0].innerHTML = '<tr><td class="textcenter" colspan="7">No se pudo realizar la operacion solicitada</td></tr>';
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

//Asigno valores y funciones a los botones y campos.
function initialize(){

    $("#btnAnterior")[0].onclick = anterior;
    $("#btnInicio")[0].onclick = clickInicio;
    $("#btnMedio")[0].onclick = clickMedio;
    $("#btnFinal")[0].onclick = clickFinal;
    $("#btnSiguiente")[0].onclick = siguiente;


    $("#agrupar")[0].onchange = buscar;

    //Disparo para cargar la pagina inicial
    $("#btnInicio")[0].onclick();
}

//Disparo para levantar el sistema de funciones.
initialize();
