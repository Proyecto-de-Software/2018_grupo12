// ------------------ Alertas ------------------
//Funcion para armar la alerta
function mostrarAlerta(texto, tipo) {
  new Message(texto, {
    duration: 4000,
    type: tipo,
    class: 'alerta'
  }).show();
}

// ------------------ Validaciones ------------------

function validarDatos() {
  var usuario = $("#usuario")[0].value;
  var contrasena = $("#contrasena")[0].value;
  var token = $('meta[name="token"]').attr('content');

  if (!(usuario && contrasena)) {
    mostrarAlerta("Complete todos los campos", "error");
    return;
  }

  $.ajax({
    url: 'autenticar',
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: { usuario: usuario, contrasena: contrasena, token: token },
    type: 'POST',
    dataType: 'json',
    success: function (respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "success":
            window.location.href = "home";
          break;
        case "error":
          mostrarAlerta(respuesta.mensaje, respuesta.estado)
          break;
        default:
          mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde", "error");
      }
    },
    error: function () { mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde", "error") }
  });
}

function initialize() {
  $("#btnAceptar")[0].onclick = validarDatos;
}

initialize();
