if ($("#tabAgregarConsulta")[0]) {
  var tipoBusqueda = document.getElementById("a_opcionPorDefecto").value;
  var tipoDoc = "";
  var nroDoc = "";
  var nroHistoriaClinica = "";
  var map;
  var zoom;
  var adminMarkers;
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
    $("#a_" +  tipoBusqueda).css({"display": "none"});
  }

  if (this.value != "no_aplica") {
    $("#a_" + this.value).css({"display": "contents"});
    tipoBusqueda = this.value;
  }else {
    $("#a_btnBuscar")[0].onclick();
  }
}

//Se ejecuta cuando se busca por algun campo
function buscar(){
  //Actualizo criterios de busqueda
  tipoBusqueda = $("#a_tipoBusqueda")[0].value;
  tipoDoc = $("#a_bus_tipoDoc")[0].value;
  nroDoc = $("#a_bus_nroDoc")[0].value;
  nroHistoriaClinica = $("#a_bus_nroHistoriaClinica")[0].value;


  //Desactivo pagina actual
  $('#a_navPags').find("[class='page-item active']")[0].className = "page-item";

  //Valores por defecto para el indice
  $("#a_anterior")[0].className = "page-item disabled";
  $("#a_inicio")[0].className = "page-item active";
  $("#a_medio")[0].className = "page-item";
  $("#a_final")[0].className = "page-item";
  $("#a_siguiente")[0].className = "page-item";

  $("#a_btnInicio")[0].innerHTML = "1";
  $("#a_btnMedio")[0].innerHTML = "2";
  $("#a_btnFinal")[0].innerHTML = "3";

  //Click al  boton "1" para disparar la busqueda
  $("#a_btnInicio")[0].onclick();
}

//Se ejecuta cuando se clickea la pagina izquierda
function clickInicio(){
  //Desactivo pagina actual
  $('#a_navPags').find("[class='page-item active']")[0].className = "page-item";

  //Guardo pagina requerida
  var pagina = this.innerHTML;
  var token = $('meta[name="token"]').attr('content');

  //Actualizo indice segun corresponda
  if (pagina != "1") {
    $("#a_medio")[0].className = "page-item active";

    this.innerHTML = parseInt(this.innerHTML) - 1;
    $("#a_btnMedio")[0].innerHTML = parseInt($("#a_btnMedio")[0].innerHTML) - 1;
    $("#a_btnFinal")[0].innerHTML = parseInt($("#a_btnFinal")[0].innerHTML) - 1;
  }else {
    $("#a_anterior")[0].className = "page-item disabled";
    $("#a_inicio")[0].className = "page-item active";
  }

  //Cosulta para cargar la pagina requerida
  $.ajax({
    url : '?action=cargarPaginaPacientesParaConsulta',
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
          $("#a_cuerpoTablaPacientes")[0].innerHTML = '<tr><td class="textcenter" colspan="6">No hay pacientes para mostrar</td></tr>';
          if (pagina == "1") {
            $("#a_medio")[0].className = "page-item disabled";
          }
          $("#a_final")[0].className = "page-item disabled";
          $("#a_siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#a_cuerpoTablaPacientes")[0].innerHTML = respuesta.contenido;
          if (pagina == 1) {
            $("#a_medio")[0].className = "page-item";
          }
          $("#a_final")[0].className = "page-item";
          $("#a_siguiente")[0].className = "page-item";
          if (respuesta.pagRestantes <= 0) {
            if (pagina == 1) {
              $("#a_medio")[0].className = "page-item disabled";
            }
            $("#a_final")[0].className = "page-item disabled";
            $("#a_siguiente")[0].className = "page-item disabled";
          }else if (respuesta.pagRestantes == 1) {
            if (pagina == 1) {
              $("#a_final")[0].className = "page-item disabled";
            }
          }
          asignarFuncionesALasOperacionesDelPaciente();
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
          $("#a_cuerpoTablaPacientes")[0].innerHTML = '<tr><td class="textcenter" colspan="6">No se pudo realizar la operacion solicitada</td></tr>';
      }
    }
  });
}

//Se ejecuta cuando clickea la pagina del medio
function clickMedio(){
  //Desactivo pagina actual
  $('#a_navPags').find("[class='page-item active']")[0].className = "page-item";

  //Guardo pagina requerida
  var pagina = this.innerHTML;
  var token = $('meta[name="token"]').attr('content');

  //Actualizo indice segun corresponda
  $("#a_anterior")[0].className = "page-item";
  $("#a_medio")[0].className = "page-item active";

  //Cosulta para cargar la pagina requerida
  $.ajax({
    url : '?action=cargarPaginaPacientesParaConsulta',
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
          $("#a_cuerpoTablaPacientes")[0].innerHTML = '<tr><td class="textcenter" colspan="6">No hay pacientes para mostrar</td></tr>';
          $("#a_final")[0].className = "page-item disabled";
          $("#a_siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#a_cuerpoTablaPacientes")[0].innerHTML = respuesta.contenido;
          $("#a_final")[0].className = "page-item";
          $("#a_siguiente")[0].className = "page-item";
          if (respuesta.pagRestantes <= 0) {
            $("#a_final")[0].className = "page-item disabled";
            $("#a_siguiente")[0].className = "page-item disabled";
          }
          asignarFuncionesALasOperacionesDelPaciente();
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
          $("#a_cuerpoTablaPacientes")[0].innerHTML = '<tr><td class="textcenter" colspan="6">No se pudo realizar la operacion solicitada</td></tr>';
      }
    }
  });
}

//Se ejecuta cuando clickea la pagina derecha
function clickFinal(){
  //Desactivo pagina actual
  $('#a_navPags').find("[class='page-item active']")[0].className = "page-item";

  //Guardo pagina requerida
  var pagina = this.innerHTML;
  var token = $('meta[name="token"]').attr('content');

  //Actualizo indice segun corresponda
  $("#a_medio")[0].className = "page-item active";

  $("#a_btnInicio")[0].innerHTML = parseInt($("#a_btnInicio")[0].innerHTML) + 1;
  $("#a_btnMedio")[0].innerHTML = parseInt($("#a_btnMedio")[0].innerHTML) + 1;
  this.innerHTML = parseInt(this.innerHTML) + 1;

  $("#a_anterior")[0].className = "page-item"

  //Cosulta para cargar la pagina requerida
  $.ajax({
    url : '?action=cargarPaginaPacientesParaConsulta',
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
          $("#a_cuerpoTablaPacientes")[0].innerHTML = '<tr><td class="textcenter" colspan="6">No hay pacientes para mostrar</td><tr>';
          $("#a_final")[0].className = "page-item disabled";
          $("#a_siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#a_cuerpoTablaPacientes")[0].innerHTML = respuesta.contenido;
          $("#a_final")[0].className = "page-item";
          $("#a_siguiente")[0].className = "page-item";
          if (respuesta.pagRestantes <= 0) {
            $("#a_final")[0].className = "page-item disabled";
            $("#a_siguiente")[0].className = "page-item disabled";
          }
          asignarFuncionesALasOperacionesDelPaciente();
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
          $("#a_cuerpoTablaPacientes")[0].innerHTML = '<tr><td class="textcenter" colspan="6">No se pudo realizar la operacion solicitada</td></tr>';
      }
    }
  });
}

//Se ejecuta cuando se clickea el boton para volver a la pag anterior
function anterior(){
  //Delega a la pag izquierda
  $("#a_btnInicio")[0].onclick()
}

//Se ejecuta cuando se clickea el boton para pasar a la pag siguiente
function siguiente(){
  //Delega a la pag que corresponda
  if ($('#a_navPags').find("[class='page-item active']")[0].id == "a_inicio") {
    $("#a_btnMedio")[0].onclick()
  }else {
    $("#a_btnFinal")[0].onclick()
  }
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
      var select = $("#a_bus_tipoDoc");
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

//Autocompleta formulario con paciente seleccionado
function autocompletar() {
  var row = this.parentNode.parentNode.cells;
  console.log(row);
  var id = this.parentNode.id;
  var token = $('meta[name="token"]').attr('content');

  $("#a_nombre").val(row[0].innerHTML);
  $("#a_apellido").val(row[1].innerHTML);
  $("#a_documento").val(row[2].innerHTML);
  $("#a_hc").val(row[3].innerHTML);
  $("#a_obrasocial").val(row[4].innerHTML);

  $.ajax({
    url : '?action=obtenerCoordenadasDerivaciones',
    data : {id: id, token: token},
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(coordenadas) {
      adminMarkers.clearMarkers();

      var markers = [];
      for (var i=0; i < coordenadas.length; i++){
        var coor = coordenadas[i].split(",");
        markers[i] = new OpenLayers.LonLat(coor[1], coor[0])
        .transform(
          new OpenLayers.Projection("EPSG:4326"),
          map.getProjectionObject()
        );
      }

      for(var i=0; i < markers.length; i++){
        adminMarkers.addMarker(new OpenLayers.Marker(markers[i]));
      }
    }
  });

  $("#btnAgregarConsulta").val(id);
}
//Asigna las funciones a los botones de las operaciones
function asignarFuncionesALasOperacionesDelPaciente(){
  document.getElementsByName("autocomplete").forEach(function(btnAutocomplete){
    btnAutocomplete.onclick = autocompletar;
  })
}
//Carga el mapa
function cargarMapa() {
  map = new OpenLayers.Map("mapdiv");
  map.addLayer(new OpenLayers.Layer.OSM());
  zoom = 6;

  adminMarkers = new OpenLayers.Layer.Markers("Markers");
  map.addLayer(adminMarkers);

  var centro=new OpenLayers.LonLat(-60.029436,-36.319530)
    .transform(
      new OpenLayers.Projection("EPSG:4326"),
      map.getProjectionObject()
    );
  map.setCenter(centro, zoom);
}

//Asigno valores y funciones a los botones y campos.
function initialize(){

  //pregunto por modulo de alta
  if ($("#tabAgregarConsulta")[0]) {
    cargarTiposDocumentos();
    cargarMapa();

    $("#a_btnAnterior")[0].onclick = anterior;
    $("#a_btnInicio")[0].onclick = clickInicio;
    $("#a_btnMedio")[0].onclick = clickMedio;
    $("#a_btnFinal")[0].onclick = clickFinal;
    $("#a_btnSiguiente")[0].onclick = siguiente;


    $("#a_btnBuscar")[0].onclick = buscar;
    $("#a_tipoBusqueda")[0].value = "no_aplica";
    $("#a_tipoBusqueda")[0].onchange = mostrarFormBusqueda;
    $("#a_bus_tipoDoc")[0].value = "";
    $("#a_bus_nroDoc")[0].value = "";
    $("#a_bus_nroHistoriaClinica")[0].value = "";

    $("#a_bus_nroDoc").bind("keypress", soloNumeros);
    $("#a_bus_nroHistoriaClinica").bind("keypress", soloNumeros);

    //Disparo para cargar la pagina inicial
    $("#a_btnInicio")[0].onclick();
  }
}

//Disparo para levantar el sistema de funciones.
initialize();
