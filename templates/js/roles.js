if ($("#tabRoles")[0]) {
  var nombre = "";
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
  nombre = $("#bus_nombre")[0].value;


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
    url : '?action=cargarPaginaRoles',
    data : {nombre : nombre, pagina: pagina},
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaRoles")[0].innerHTML = '<tr><td style="text-align: center" colspan="2">No hay roles para mostrar</td></tr>';
          if (pagina == "1") {
            $("#medio")[0].className = "page-item disabled";
          }
          $("#final")[0].className = "page-item disabled";
          $("#siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#cuerpoTablaRoles")[0].innerHTML = respuesta.contenido;
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
          $("#cuerpoTablaRoles")[0].innerHTML = '<tr><td style="text-align: center" colspan="2">No se pudo realizar la operacion solicitada</td></tr>';
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
    url : '?action=cargarPaginaRoles',
    data : {nombre : nombre, pagina: pagina},
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaRoles")[0].innerHTML = '<tr><td style="text-align: center" colspan="7">No hay roles para mostrar</td></tr>';
          $("#final")[0].className = "page-item disabled";
          $("#siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#cuerpoTablaRoles")[0].innerHTML = respuesta.contenido;
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
          $("#cuerpoTablaRoles")[0].innerHTML = '<tr><td style="text-align: center" colspan="7">No se pudo realizar la operacion solicitada</td></tr>';
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
    url : '?action=cargarPaginaRoles',
    data : {nombre : nombre, pagina: pagina},
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaRoles")[0].innerHTML = '<tr><td style="text-align: center" colspan="7">No hay roles para mostrar</td></tr>';
          $("#final")[0].className = "page-item disabled";
          $("#siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#cuerpoTablaRoles")[0].innerHTML = respuesta.contenido;
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
          $("#cuerpoTablaRoles")[0].innerHTML = '<tr><td style="text-align: center" colspan="7">No se pudo realizar la operacion solicitada</td></tr>';
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
//------------------ Baja de roles ------------------
function mostrarMensajeEliminacion(){
  $("#tituloMensaje").html("Eliminar rol");
  $("#cuerpoMensaje").html("¿Esta seguro de que desea eliminar este rol?")

  $("#botonMensaje")[0].rol = this.parentNode.id;
  $("#botonMensaje")[0].onclick = eliminarRol;

  $("#mensajeConfirmacion").modal();
}

function eliminarRol(){
  var id = this.rol;
  $.ajax({
    url : '?action=eliminarRol',
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

//------------------ Alta de roles ------------------

//Funcion para agregar rol
function agregarRol(){
  var nombre = $("#a_nombre").val();

  $.ajax({
    url : '?action=altaRol',
    data : {nombre: nombre},
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si se realizo la operacion correctamente
      switch (respuesta.estado) {
        case "success":
          mostrarAlerta(respuesta.mensaje,respuesta.estado);
          $('#formularioAgregarRol').trigger("reset");
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
//------------------ Moficacion de roles ------------------
function mostrarFormularioModificacion() {
  mostrarFormulario(this.parentNode.id, false);
}
function mostrarFormulario(id, actualizar){
  $.ajax({
    url : '?action=infoRol',
    data : { id: id },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      if (respuesta.estado == "success") {
        if (actualizar) {
          $("#m_permisosDelRol").html("");
        }
        $("#m_nombre").val(respuesta.rol.nombre);
        $("#m_tituloRol").html("Permisos del rol " + respuesta.rol.nombre);

        if (respuesta.rol.permisos.length === 0) {
          $("#m_permisosDelRol").html('<div class="textcenter">No tiene</div>');
        }else {
          var string = '<div class="mb-2 bordeclass p-1"> ??1 '+
          '<button type="button" class="close" name="btnQuitarRol" aria-label="Boton para quitar permiso ??2 " id="permiso_??3">'+
          '<span aria-hidden="true">&times;</span></button></div>';
          $.each(respuesta.rol.permisos, function(key,permiso) {
            var opcion = string.replace("??1", permiso.nombre);
            var opcion = opcion.replace("??2", permiso.nombre);
            var opcion = opcion.replace("??3", permiso.id);
            $("#m_permisosDelRol").append(opcion);
          });

          $.each($("#m_permisosDelRol div button"), function(key,boton){
            boton.idRol = respuesta.rol.id;
            boton.onclick = eliminarPermiso;
          });

          $("#btnModificarRol")[0].idRol = respuesta.rol.id;
          $("#btnModificarRol")[0].onclick = modificarRol;
        }

        $("#btnModificarRol")[0].rol = respuesta.rol.id;
        $("#btnModificarRol")[0].onclick = modificarRol;
        $("#tabModificarRol").css({"display":"block"});
        $("#btnAgregarPermiso")[0].rol = respuesta.rol.id;
        $("#btnAgregarPermiso")[0].onclick = agregarPermisos;
        $('#menuTabs a[href="#contenidoModificarRol"]').tab('show');
      }else {
        mostrarAlerta(respuesta.mensaje,respuesta.estado);
      }
    }
  });
}

function filtrarOpciones() {
  $("#m_permiso option").css( "display", "none" );
  $('#m_permiso option:contains("' + this.value + '")').css( "display", "block" );
}

function modificarRol(){
  //id del paciente a modificar
  var id = this.rol;
  var nombre = $("#m_nombre").val();

  $.ajax({
    url : '?action=modificarRol',
    data : { id:id, nombre: nombre },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Tengo en cuenta los posibles casos
      switch(respuesta.estado){
        case "success":
          mostrarAlerta(respuesta.mensaje, respuesta.estado);
          $("#m_tituloRol").html("Permisos del rol " + nombre);
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

function eliminarPermiso() {
  var idRol = this.idRol;
  var idPermiso = this.id.split("_")[1];

  $.ajax({
    url : '?action=quitarPermisoAlRol',
    data : { idRol: idRol, idPermiso: idPermiso },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      switch (respuesta.estado) {
        case "success":
          mostrarAlerta(respuesta.mensaje, respuesta.estado);
          this.remove();
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

function agregarPermisos(){
  var idPermisos = $("#m_permiso").val();
  var idRol = this.rol;

  $.ajax({
    url : '?action=agregarPermisos',
    data : { idRol: idRol, idPermisos: idPermisos },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      switch (respuesta.estado) {
        case "success":
          mostrarAlerta(respuesta.mensaje, respuesta.estado);
          mostrarFormulario(idRol,true);
          $("#m_permiso").val("");
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
if ($("#contenidoModificarRol")[0]) {
  // Para quitar el formulario para modificar el rol cuando se clickee la pestaña "Roles"
  $('#menuTabs a[href="#contenidoRoles"]').bind('click', function (e) {
    e.preventDefault()
    $("#tabModificarRol").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $('#formularioModificarRol').trigger("reset");
      $("#m_permisosDelRol").html("");
      $('#btnModificarRol')[0].onclick = "";
      $("#btnModificarRol")[0].rol = "";
    }, 250);
  })

  // Para quitar el formulario para modificar el rol cuando se clickee la pestaña "Agregar"
  $('#menuTabs a[href="#contenidoAgregarRol"]').bind('click', function (e) {
    e.preventDefault()
    $("#tabModificarRol").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $('#formularioModificarRol').trigger("reset");
      $("#m_permisosDelRol").html("");
      $('#btnModificarRol')[0].onclick = "";
      $("#btnModificarRol")[0].rol = "";
    }, 250);
  })
}

//Asigna las funciones a los botones de las operaciones
function asignarFuncionesALasOperaciones(){
  document.getElementsByName("eliminar").forEach(function(btnEliminar){
    btnEliminar.onclick = mostrarMensajeEliminacion;
  })

  document.getElementsByName("modificar").forEach(function(btnModificar){
    btnModificar.onclick = mostrarFormularioModificacion;
  })

}

//Asigno valores y funciones a los botones y campos.
function initialize(){

  //pregunto por modulo de alta
  if ($("#tabAgregarRol")[0]) {
    $("#btnAgregarRol")[0].onclick = agregarRol;
  }

  //pregunto por modulo de modificacion
  if ($("#tabModificarRol")[0]) {
    $("#filtroOpciones")[0].onkeyup = filtrarOpciones;
  }

  //Pregunto por modulo de listado (contiene baja y show)
  if ($("#tabRoles")[0]) {
    $("#btnAnterior")[0].onclick = anterior;
    $("#btnInicio")[0].onclick = clickInicio;
    $("#btnMedio")[0].onclick = clickMedio;
    $("#btnFinal")[0].onclick = clickFinal;
    $("#btnSiguiente")[0].onclick = siguiente;

    $("#btnBuscar")[0].onclick = buscar;
    $("#bus_nombre")[0].value = "";
    //Disparo para cargar la pagina inicial
    $("#btnInicio")[0].onclick();
  }else{
    $('#menuTabs a[href="#contenidoAgregarRol"]').click();
  }
}

//Disparo para levantar el sistema de funciones.
initialize();
