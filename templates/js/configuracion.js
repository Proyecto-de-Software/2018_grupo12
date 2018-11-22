// ------------------ Alertas ------------------
//Funcion para armar la alerta
function mostrarAlerta(texto, tipo){
  new Message(texto, {
    duration: 4000,
    type: tipo,
    class : 'alerta'
  }).show();
}

// ------------------ Guardado ------------------

function guardarDatos() {
  var titulo = $("#titulo")[0].value;
  var descripcion = $("#descripcion")[0].value;
  var email = $("#email")[0].value;
  var limite = $("#limite")[0].value;
  var habilitado = $("#habilitado")[0].value;
  var token = $('meta[name="token"]').attr('content');

  if (!(titulo && descripcion && email && limite) || habilitado == "" ){
    mostrarAlerta("Complete todos los campos","error");
    return;
  }

  $.ajax({
    url : '?action=guardarConfiguracion',
    data : { titulo : titulo, descripcion : descripcion, email : email,
             limite : limite, habilitado : habilitado, token: token},
    type : 'POST',
    dataType: 'json',
    // código a ejecutar si la petición es satisfactoria;
    // la respuesta es pasada como argumento a la función
    success : function(respuesta) {
      //Pregunto si hay elementos o no y actualizo segun corresponda
      if (respuesta.estado) {
        mostrarAlerta(respuesta.mensaje, respuesta.estado)
      }
    }
  });
}

function initialize() {
  if ($("#btnGuardar")[0]) {
    $("#btnGuardar")[0].onclick = guardarDatos;
  }
}

initialize();
