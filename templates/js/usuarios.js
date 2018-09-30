var username = "";
var estado = document.getElementById("opcionPorDefecto").value;

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
    url : '?action=cargarPagina',
    data : { username : username, estado : estado, pagina : pagina },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaUsuarios")[0].innerHTML = '<td align="center" colspan="6">No hay usuarios para mostrar</td>';
          if (pagina == "1") {
            $("#medio")[0].className = "page-item disabled";
          }
          $("#final")[0].className = "page-item disabled";
          $("#siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#cuerpoTablaUsuarios")[0].innerHTML = respuesta.contenido;
          $("#final")[0].className = "page-item";
          $("#siguiente")[0].className = "page-item";
          asignarFuncionesALasOperaciones();
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
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
    url : '?action=cargarPagina',
    data : { username : username, estado : estado, pagina : pagina },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaUsuarios")[0].innerHTML = '<td align="center" colspan="6">No hay usuarios para mostrar</td>';
          $("#final")[0].className = "page-item disabled";
          $("#siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#cuerpoTablaUsuarios")[0].innerHTML = respuesta.contenido;
          asignarFuncionesALasOperaciones();
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
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
    url : '?action=cargarPagina',
    data : { username : username, estado : estado, pagina : pagina },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "no hay":
          $("#cuerpoTablaUsuarios")[0].innerHTML = '<td align="center" colspan="6">No hay usuarios para mostrar</td>';
          $("#final")[0].className = "page-item disabled";
          $("#siguiente")[0].className = "page-item disabled";
          break;
        case "si hay":
          $("#cuerpoTablaUsuarios")[0].innerHTML = respuesta.contenido;
          asignarFuncionesALasOperaciones();
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
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
function mostrarMensajeEliminacion(){
  $("#tituloMensaje").html("Eliminar usuario");
  $("#cuerpoMensaje").html("¿Esta seguro de que desea eliminar este usuario?")

  $("#botonMensaje")[0].usuario = this.parentNode.id;
  $("#botonMensaje")[0].onclick = eliminarUsuario;

  $("#mensajeConfirmacion").modal();
}

function eliminarUsuario(){
  var id = this.usuario;
  $.ajax({
    url : '?action=eliminarUsuario',
    data : { id: id },
    type : 'POST',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si se realizo la operacion correctamente
      if (respuesta == "eliminado") {
        mostrarAlerta("Usuario eliminado correctamente","success")
        document.getElementsByClassName("page-item active")[0].children[0].onclick();
      }else {
        mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
      }
    }
  });
}

function mostrarMensajeBloqueo(){
  $("#tituloMensaje").html("Bloquear usuario");
  $("#cuerpoMensaje").html("¿Esta seguro de que desea bloquear este usuario?")

  $("#botonMensaje")[0].usuario = this.parentNode.id;
  $("#botonMensaje")[0].onclick = bloquearUsuario;

  $("#mensajeConfirmacion").modal();
}

function bloquearUsuario(){
  var id = this.usuario;
  $.ajax({
    url : '?action=bloquearUsuario',
    data : { id: id },
    type : 'POST',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si se realizo la operacion correctamente
      if (respuesta == "bloqueado") {
        mostrarAlerta("Usuario bloqueado correctamente","success");
        document.getElementsByClassName("page-item active")[0].children[0].onclick();
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
  $.ajax({
    url : '?action=activarUsuario',
    data : { id: id },
    type : 'POST',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si se realizo la operacion correctamente
      if (respuesta == "activado") {
        mostrarAlerta("Usuario activado correctamente","success");
        document.getElementsByClassName("page-item active")[0].children[0].onclick();
      }else {
        mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
      }
    }
  });
}

//------------------ Agregar usuario ------------------
function agregarUsuario(){
  //Tomo datos del formulario
  var nombre = $("#nombre")[0].value;
  var apellido = $("#apellido")[0].value;
  var nombreDeUsuario = $("#nombreDeUsuario")[0].value;
  var contrasena = $("#contrasena")[0].value;
  var email = $("#email")[0].value;

  $.ajax({
    url : '?action=agregarUsuario',
    data : { nombre: nombre, apellido:apellido, nombreDeUsuario:nombreDeUsuario, contrasena:contrasena, email:email },
    type : 'POST',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Tengo en cuenta los posibles casos
      switch(respuesta) {
        case "guardado correcto":
          mostrarAlerta("Usuario guardado correctamente","success");
          $('#formularioAgregarUsuario').trigger("reset");
          document.getElementsByClassName("page-item active")[0].children[0].onclick();
          break;
        case "datos incorrectos":
          mostrarAlerta("Complete todos los campos","error");
          break;
        case "email incorrecto":
          mostrarAlerta("Email ingresado es incorrecto","error");
          break;
        case "nombre de usuario existe":
          mostrarAlerta("El nombre de usuario ya esta registrado","error");
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
  $.ajax({
    url : '?action=formularioModificacionUsuario',
    data : { id: id },
    type : 'POST',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      $("#contenidoModificarUsuario").html(respuesta);
      $("#btnModificarUsuario")[0].usuario = id;
      $("#btnModificarUsuario")[0].onclick = modificarUsuario;
      $("#tabModificarUsuario").css({"display":"block"});
      $('#menuTabs li:nth-child(3) a').tab('show');
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

  $.ajax({
    url : '?action=modificarUsuario',
    data : { id:id, nombre:nombre, apellido:apellido, contrasena:contrasena, email:email },
    type : 'POST',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Tengo en cuenta los posibles casos
      switch(respuesta) {
        case "modificado correcto":
          mostrarAlerta("Usuario modificado correctamente","success");
          document.getElementsByClassName("page-item active")[0].children[0].onclick();
          $('#menuTabs li:nth-child(1) a').click();
          break;
        case "datos incorrectos":
          mostrarAlerta("Complete todos los campos","error");
          break;
        case "email incorrecto":
          mostrarAlerta("Email ingresado es incorrecto","error");
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion vuelva a intentar mas tarde","error");
      }
    }
  });

}

// Para quitar el formulario para modificar el usuario cuando se clickee la pestaña "Usuarios"
$('#menuTabs li:nth-child(1) a').on('click', function (e) {
  e.preventDefault()
  $("#tabModificarUsuario").css({"display":"none"});
  $(this).tab('show');
  setTimeout(function() {
    $("#contenidoModificarUsuario").html("...");
  }, 250);
})

// Para quitar el formulario para modificar el usuario cuando se clickee la pestaña "Agregar"
$('#menuTabs li:nth-child(2) a').on('click', function (e) {
  e.preventDefault()
  $("#tabModificarUsuario").css({"display":"none"});
  $(this).tab('show');
  setTimeout(function() {
    $("#contenidoModificarUsuario").html("...");
  }, 250);
})

//------------------ Administrar roles usuario ------------------
//Usado en el boton para desplegar modal
function mostrarPanelAdministracionRoles() {
  var id = this.parentNode.id;
  actualizarPanelAdministracionRoles(id)
}

//Usado para actualizar el contenido del modal
function actualizarPanelAdministracionRoles(id) {
  $.ajax({
    url : '?action=cuerpoPanelAdministracionRoles',
    data : { id: id },
    type : 'POST',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      if (respuesta == "error") {
        mostrarAlerta("No se pudo realizar la operacion vuelva a intentar mas tarde","error");
      }else {
        $("#cuerpoPanelAdministracionRoles").html(respuesta);
        $("#btnAgregarRol")[0].usuario = id;
        $("#btnAgregarRol")[0].onclick = agregarRol;

        document.getElementsByName("btnQuitarRol").forEach(function(btnQuitarRol){
          btnQuitarRol.onclick= quitarRol;
        });

        $("#panelAdministracionRoles").modal();
      }
    }
  });
}

function agregarRol() {
  var id = this.usuario;
  var idRol = $("#rol")[0].value;

  $.ajax({
    url : '?action=agregarRol',
    data : { id: id, idRol: idRol },
    type : 'POST',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      switch(respuesta) {
        case "agregado correcto":
          mostrarAlerta("Rol agregado correctamente","success");
          actualizarPanelAdministracionRoles(id);
          //$('#formulario').trigger("reset");
          //actualizo listado de usuarios
          document.getElementsByClassName("page-item active")[0].children[0].onclick();
          break;
        case "ya tiene este rol":
          mostrarAlerta("El usuario ya tiene asignado este rol","error");
          break;
        case "no seleccionado":
          mostrarAlerta("No se selecciono ningun rol","error");
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

  $.ajax({
    url : '?action=quitarRol',
    data : { id: id, idRol: idRol },
    type : 'POST',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      switch(respuesta) {
        case "quitado correcto":
          mostrarAlerta("Rol quitado correctamente","success");
          actualizarPanelAdministracionRoles(id);
          //$('#formulario').trigger("reset");
          //actualizo listado de usuarios
          document.getElementsByClassName("page-item active")[0].children[0].onclick();
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
//Asigna las funciones a los botones de las operaciones
function asignarFuncionesALasOperaciones(){
  //Asigno funciones al boton para eliminar usuario
  document.getElementsByName("eliminar").forEach(function(btnEliminar){
    btnEliminar.onclick= mostrarMensajeEliminacion;
  })

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
  $("#btnAnterior")[0].onclick = anterior;
  $("#btnInicio")[0].onclick = clickInicio;
  $("#btnMedio")[0].onclick = clickMedio;
  $("#btnFinal")[0].onclick = clickFinal;
  $("#btnSiguiente")[0].onclick = siguiente;
  $("#btnBuscar")[0].onclick = buscar;
  $("#btnAgregarUsuario")[0].onclick = agregarUsuario;

  $("#estado")[0].value = "no aplica";
  $("#username")[0].value = "";

  //Disparo para cargar la pagina inicial
  $("#btnInicio")[0].onclick();
}

//Disparo para levantar el sistema de funciones.
initialize();
