if ($("#tabUsuarios")[0]) {
  var username = "";
  var estado = document.getElementById("opcionPorDefecto").value;
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
  username = $("#username")[0].value;
  estado = $("#estado")[0].value;

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
  var token = $('meta[name="token"]').attr('content');

  //Actualizo indice segun corresponda
  if (pagina != "1") {
    $("#medio")[0].className = "page-item active";

    this.innerHTML = parseInt(this.innerHTML) - 1;
    $("#btnMedio")[0].innerHTML = parseInt($("#btnMedio")[0].innerHTML) - 1;
    $("#btnFinal")[0].innerHTML = parseInt($("#btnFinal")[0].innerHTML) - 1;
  }else {
    $("#inicio")[0].className = "page-item active";
    $("#anterior")[0].className = "page-item disabled";
  }

  //Cosulta para cargar la pagina requerida
  $.ajax({
    url : '?action=cargarPaginaUsuarios',
    data : { username : username, estado : estado, pagina : pagina, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaUsuarios")[0].innerHTML = '<tr><td class="textcenter" colspan="7">No hay usuarios para mostrar</td></tr>';
          if (pagina == "1") {
            $("#medio")[0].className = "page-item disabled";
          }
          $("#final")[0].className = "page-item disabled";
          $("#siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#cuerpoTablaUsuarios")[0].innerHTML = respuesta.contenido;
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
          $("#cuerpoTablaUsuarios")[0].innerHTML = '<tr><td class="textcenter" colspan="7">No se pudo realizar la operacion solicitada</td></tr>';
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
  var token = $('meta[name="token"]').attr('content');

  //Actualizo indice segun corresponda
  $("#anterior")[0].className = "page-item";
  $("#medio")[0].className = "page-item active";

  //Cosulta para cargar la pagina requerida
  $.ajax({
    url : '?action=cargarPaginaUsuarios',
    data : { username : username, estado : estado, pagina : pagina, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaUsuarios")[0].innerHTML = '<tr><td class="textcenter" colspan="7">No hay usuarios para mostrar</td></tr>';
          $("#final")[0].className = "page-item disabled";
          $("#siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#cuerpoTablaUsuarios")[0].innerHTML = respuesta.contenido;
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
          $("#cuerpoTablaUsuarios")[0].innerHTML = '<tr><td class="textcenter" colspan="7">No se pudo realizar la operacion solicitada</td></tr>';
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
  var token = $('meta[name="token"]').attr('content');

  //Actualizo indice segun corresponda
  $("#medio")[0].className = "page-item active";

  $("#btnInicio")[0].innerHTML = parseInt($("#btnInicio")[0].innerHTML) + 1;
  $("#btnMedio")[0].innerHTML = parseInt($("#btnMedio")[0].innerHTML) + 1;
  this.innerHTML = parseInt(this.innerHTML) + 1;

  $("#anterior")[0].className = "page-item"

  //Cosulta para cargar la pagina requerida
  $.ajax({
    url : '?action=cargarPaginaUsuarios',
    data : { username : username, estado : estado, pagina : pagina, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaUsuarios")[0].innerHTML = '<tr><td class="textcenter" colspan="7">No hay usuarios para mostrar</td></tr>';
          $("#final")[0].className = "page-item disabled";
          $("#siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#cuerpoTablaUsuarios")[0].innerHTML = respuesta.contenido;
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
          $("#cuerpoTablaUsuarios")[0].innerHTML = '<tr><td class="textcenter" colspan="7">No se pudo realizar la operacion solicitada</td></tr>';
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

//------------------ Operaciones sobre los usuarios ------------------
function mostrarMensajeBloqueo(){
  $("#tituloMensaje").html("Bloquear usuario");
  $("#cuerpoMensaje").html("¿Esta seguro de que desea bloquear este usuario?")

  $("#botonMensaje")[0].usuario = this.parentNode.id;
  $("#botonMensaje")[0].onclick = bloquearUsuario;

  $("#mensajeConfirmacion").modal();
}

function bloquearUsuario(){
  var id = this.usuario;
  var token = $('meta[name="token"]').attr('content');

  if (! id) {
    mostrarAlerta("Usuario no especificado","error");
    return;
  }

  $.ajax({
    url : '?action=bloquearUsuario',
    data : { id: id, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si se realizo la operacion correctamente
      if (respuesta.estado == "bloqueado") {
        mostrarAlerta("Usuario bloqueado correctamente","success");
        document.getElementsByClassName("page-item active")[0].children[0].onclick();
      }else if (respuesta.estado == "auto_bloqueo") {
        mostrarAlerta("No puedes bloquearte a ti mismo","error");
      }else if (respuesta.estado == "un_solo_admin") {
        mostrarAlerta("Queda un unico administrador, no puedes bloquearlo","error");
      }else {
        mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
      }
    }
  });
}

function mostrarMensajeActivacion(){
  $("#tituloMensaje").html("Activar usuario");
  $("#cuerpoMensaje").html("¿Esta seguro de que desea activar este usuario?")

  $("#botonMensaje")[0].usuario = this.parentNode.id;
  $("#botonMensaje")[0].onclick = activarUsuario;

  $("#mensajeConfirmacion").modal();
}

function activarUsuario(){
  var id = this.usuario;
  var token = $('meta[name="token"]').attr('content');

  if (! id) {
    mostrarAlerta("Usuario no especificado","error");
    return;
  }

  $.ajax({
    url : '?action=activarUsuario',
    data : { id: id, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si se realizo la operacion correctamente
      if (respuesta.estado == "activado") {
        mostrarAlerta("Usuario activado correctamente","success");
        document.getElementsByClassName("page-item active")[0].children[0].onclick();
      }else if (respuesta.estado == "auto_activacion") {
        mostrarAlerta("No puedes activarte a ti mismo","error");
      }else {
        mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
      }
    }
  });
}

//------------------ Agregar usuario ------------------
// Se liga a un input para aceptar solo letras y espacios
function soloLetras(event){
  var inputValue = event.which;
  // allow letters and whitespaces only.
  if(!( (inputValue >= 65 && inputValue <= 90) ||
        (inputValue >= 97 && inputValue <= 122) ||
        (inputValue == 32) || (inputValue == 0) ||
        (inputValue == 8) || (inputValue == 241) || (inputValue == 209)
      )
    ){
    event.preventDefault();
  }
}
//Se liga a un input para no aceptar mayusculas ni espacios
function sinMayusculasNiEspacios(event){
  var inputValue = event.which;
  // allow letters and whitespaces only.
  if( (inputValue >= 65 && inputValue <= 90) ||
      (inputValue == 209) || (inputValue == 32)
    ){
    event.preventDefault();
  }
}

function agregarUsuario(){
  //Tomo datos del formulario
  var nombre = $("#nombre")[0].value;
  var apellido = $("#apellido")[0].value;
  var nombreDeUsuario = $("#nombreDeUsuario")[0].value;
  var contrasena = $("#contrasena")[0].value;
  var email = $("#email")[0].value;
  var token = $('meta[name="token"]').attr('content');

  if (! (nombre && apellido && email && contrasena && nombreDeUsuario)) {
    mostrarAlerta("Complete todos los campos","error");
    return;
  }else if (contrasena.length < 8) {
    mostrarAlerta("La contraseña debe tener por lo menos 8 caracteres","error");
    return;
  }

  $.ajax({
    url : '?action=agregarUsuario',
    data : { nombre: nombre, apellido:apellido, nombreDeUsuario:nombreDeUsuario, contrasena:contrasena, email:email, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Tengo en cuenta los posibles casos
      switch(respuesta.estado){
        case "success":
          mostrarAlerta(respuesta.mensaje,respuesta.estado);
          $('#formularioAgregarUsuario').trigger("reset");
          document.getElementsByClassName("page-item active")[0].children[0].onclick();
          break;
        case "error":
          mostrarAlerta(respuesta.mensaje,respuesta.estado);
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion vuelva a intentar mas tarde","error");
      }
    }
  });
}

//------------------ Modificar usuario ------------------
function mostrarFormularioModificacion(){
  var id = this.parentNode.id;
  var token = $('meta[name="token"]').attr('content');

  if (! id) {
    mostrarAlerta("Usuario no especificado","error");
    return;
  }

  $.ajax({
    url : '?action=formularioModificacionUsuario',
    data : { id: id, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      if (respuesta.estado == "success") {
        $("#contenidoModificarUsuario").html(respuesta.contenido);
        $("#btnModificarUsuario")[0].usuario = id;
        $("#btnModificarUsuario")[0].onclick = modificarUsuario;
        $("#nombreModificacion").bind("keypress", soloLetras);
        $("#apellidoModificacion").bind("keypress", soloLetras);
        $("#tabModificarUsuario").css({"display":"block"});
        $('#menuTabs a[href="#contenidoModificarUsuario"]').tab('show');
      }else {
        mostrarAlerta("No se pudo realizar la operacion vuelva a intentar mas tarde","error");
      }
    }
  });
}

function modificarUsuario(){
  //id del usuario a modificar
  var id = this.usuario;

  //Tomo datos del formulario
  var nombre = $("#nombreModificacion")[0].value;
  var apellido = $("#apellidoModificacion")[0].value;
  var contrasena = $("#contrasenaModificacion")[0].value;
  var email = $("#emailModificacion")[0].value;
  var token = $('meta[name="token"]').attr('content');

  if (! (nombre && apellido && email)) {
    mostrarAlerta("Complete todos los campos","error");
    return;
  }else if (contrasena && contrasena.length < 8) {
    mostrarAlerta("La contraseña debe tener por lo menos 8 caracteres","error");
    return;
  }

  $.ajax({
    url : '?action=modificarUsuario',
    data : { id:id, nombre:nombre, apellido:apellido, contrasena:contrasena, email:email, token: token },
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
          $('#menuTabs a[href="#contenidoUsuarios"]').click();
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

//------------------ Administrar roles usuario ------------------
//Usado en el boton para desplegar modal
function mostrarPanelAdministracionRoles() {
  var id = this.parentNode.id;
  actualizarPanelAdministracionRoles(id)
}

//Usado para actualizar el contenido del modal
function actualizarPanelAdministracionRoles(id) {
  var token = $('meta[name="token"]').attr('content');

  if (! id) {
    mostrarAlerta("Usuario no especificado","error");
    return;
  }

  $.ajax({
    url : '?action=cuerpoPanelAdministracionRoles',
    data : { id: id, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      if (respuesta.estado == "success") {
        $("#cuerpoPanelAdministracionRoles").html(respuesta.contenido);
        $("#btnAgregarRol")[0].usuario = id;
        $("#btnAgregarRol")[0].onclick = agregarRol;

        document.getElementsByName("btnQuitarRol").forEach(function(btnQuitarRol){
          btnQuitarRol.onclick= quitarRol;
        });

        $("#panelAdministracionRoles").modal();
      }else if (respuesta.estado == "error") {
        mostrarAlerta(respuesta.mensaje, respuesta.estado);
      }else {
        mostrarAlerta("No se pudo realizar la operacion vuelva a intentar mas tarde","error");
      }
    }
  });
}

function agregarRol() {
  var id = this.usuario;
  var idRol = $("#rol")[0].value;
  var token = $('meta[name="token"]').attr('content');

  if (! (id && idRol)) {
    mostrarAlerta("Usuario o rol no especificados","error");
    return;
  }

  $.ajax({
    url : '?action=agregarRol',
    data : { id: id, idRol: idRol, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      switch(respuesta.estado) {
        case "success":
          mostrarAlerta(respuesta.mensaje, respuesta.estado);
          actualizarPanelAdministracionRoles(id);
          //$('#formulario').trigger("reset");
          //actualizo listado de usuarios
          document.getElementsByClassName("page-item active")[0].children[0].onclick();
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

function quitarRol() {
  var id = $("#btnAgregarRol")[0].usuario;
  var idRol = this.id;
  var token = $('meta[name="token"]').attr('content');

  if (! (id && idRol)) {
    mostrarAlerta("Usuario o rol no especificados","error");
    return;
  }

  $.ajax({
    url : '?action=quitarRol',
    data : { id: id, idRol: idRol, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      switch(respuesta.estado) {
        case "success":
          mostrarAlerta(respuesta.mensaje, respuesta.estado);
          actualizarPanelAdministracionRoles(id);
          //$('#formulario').trigger("reset");
          //actualizo listado de usuarios
          document.getElementsByClassName("page-item active")[0].children[0].onclick();
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

//Borrar datos del panel cuando se oculte
$('#panelAdministracionRoles').on('hidden.bs.modal', function (e) {
  $("#cuerpoPanelAdministracionRoles").html("");
})

//------------------ Inicializar ------------------
//Pregunto si tiene dicha funcionalidad
if ($("#contenidoModificarUsuario")[0]) {

  // Para quitar el formulario para modificar el usuario cuando se clickee la pestaña "Usuarios"
  $('#menuTabs a[href="#contenidoUsuarios"]').on('click', function (e) {
    e.preventDefault()
    $("#tabModificarUsuario").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $("#contenidoModificarUsuario").html("...");
    }, 250);
  })

  // Para quitar el formulario para modificar el usuario cuando se clickee la pestaña "Agregar"
  $('#menuTabs a[href="#contenidoAgregarUsuario"]').on('click', function (e) {
    e.preventDefault()
    $("#tabModificarUsuario").css({"display":"none"});
    $(this).tab('show');
    setTimeout(function() {
      $("#contenidoModificarUsuario").html("...");
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
  if ($("#btnAgregarUsuario")[0]) {
    $("#btnAgregarUsuario")[0].onclick = agregarUsuario;
    $("#nombre").bind("keypress", soloLetras);
    $("#apellido").bind("keypress", soloLetras);
    $("#nombreDeUsuario").bind("keypress", sinMayusculasNiEspacios);
  }

  if ($("#tabUsuarios")[0]) {
    $("#btnAnterior")[0].onclick = anterior;
    $("#btnInicio")[0].onclick = clickInicio;
    $("#btnMedio")[0].onclick = clickMedio;
    $("#btnFinal")[0].onclick = clickFinal;
    $("#btnSiguiente")[0].onclick = siguiente;
    $("#btnBuscar")[0].onclick = buscar;
    $("#estado")[0].value = "no aplica";
    $("#username")[0].value = "";
    //Disparo para cargar la pagina inicial
    $("#btnInicio")[0].onclick();
  }else {
    $('#menuTabs a[href="#contenidoAgregarUsuario"]').click();
  }
}

//Disparo para levantar el sistema de funciones.
initialize();
