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

  if (!(usuario && contrasena)) {
    mostrarAlerta("Complete todos los campos", "error");
    return;
  }

  // Autentico en la sesion y despues en la api
  $.ajax({
    url: 'autenticar',
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: { usuario: usuario, contrasena: contrasena },
    type: 'POST',
    dataType: 'json',
    success: function (respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      switch (respuesta.estado) {
        case "success":
            $.ajax({
                url: 'oauth/token',
                data: { client_secret: "flhDgCqpm41KXRRCCjnW9JzpRo9vjsMUQEk4kw2U", grant_type: "password", client_id: 2,
                        username: usuario, password: contrasena },
                type: 'POST',
                dataType: 'json',
                success: function (respuesta) {
                    sessionStorage.setItem("apiToken", respuesta.access_token);
                    window.location.href = "home";
                },
                error: function () {
                    mostrarAlerta("No se pudo realizar la operacion, vuelva a intentar mas tarde", "error")
                }
            });
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
