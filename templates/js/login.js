// ------------------ Alertas ------------------
//Funcion para armar la alerta
function mostrarAlerta(texto, tipo){
  new Message(texto, {
    duration: 4000,
    type: tipo,
    class : 'alerta'
  }).show();
}

// ------------------ Validaciones ------------------

function validarDatos() {
  var usuario = $("#usuario")[0].value;
  var contrasena = $("#contrasena")[0].value;
  var token = $('meta[name="token"]').attr('content');

  $.ajax({
    url : '?action=validar',
    data : { usuario : usuario, contrasena : contrasena, token: token },
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "success":
          window.location.href = "index.php?action=home";
          break;
        case "error":
          mostrarAlerta(respuesta.mensaje, respuesta.estado)
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde","error");
      }
    }
  });
}

function initialize() {
  $("#btnAceptar")[0].onclick = validarDatos;
}

initialize();
