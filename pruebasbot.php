<?php
  
$returnArray = true;
$rawData = file_get_contents('php://input');
$response = json_decode($rawData, $returnArray);
$id_del_chat = $response['message']['chat']['id'];

 
// Obtener comando (y sus posibles parametros)
$regExp = '#^(\/[a-zA-Z0-9\/]+?)(\ .*?)$#i';


$tmp = preg_match($regExp, $response['message']['text'], $aResults);

if (isset($aResults[1])) {
    $cmd = trim($aResults[1]);
    $cmd_params = trim($aResults[2]);
} else {
    $cmd = trim($response['message']['text']);
    $cmd_params = '';
}
 
// Mensaje de respuesta
$msg = array();
$msg['chat_id'] = $response['message']['chat']['id'];
$msg['text'] = null;
$msg['disable_web_page_preview'] = true;
$msg['reply_to_message_id'] = (int)$response['message']['message_id'];
$msg['reply_markup'] = null;
 
// Comandos
switch ($cmd) {
    case '/start':
        $msg['text']  = 'Hola ' . $response['message']['from']['first_name'] . PHP_EOL;
        $msg['text'] .= 'este es el bot del hospital Dr.Alejandro Korn para consultar la informacion sobre instituciones, para conocer los comandos validos envía /help';
        $msg['reply_to_message_id'] = null;
        break;
 
    case '/help':
        $msg['text']  = 'Los comandos disponibles son estos:' . PHP_EOL;
        $msg['text'] .= '/start Inicializa el bot' . PHP_EOL;
        $msg['text'] .= '/instituciones Muestra lista de instituciones con su informacion' . PHP_EOL;
        $msg['text'] .= '/instituciones-region-sanitaria:region-sanitaria Lista de instituciones para es region sanitaria';
        $msg['reply_to_message_id'] = null;
        break;
 
    case '/instituciones':
    $url ='https://grupo12.proyecto2018.linti.unlp.edu.ar/apiRest/api.php/instituciones';
    $json = file_get_contents($url);
    $array = json_decode($json,true);
    $msg['text']  = 'Las instituciones disponibles son:'. PHP_EOL;
    foreach($array as $a){
        $msg['text']  .= 'Institucion: '.$a['nombre'].', Direccion: '.$a['direccion'].', Telefono: '. $a['telefono']. PHP_EOL;
    }
     break;
 
    case '/instituciones-region-sanitaria:':
    $url ='https://grupo12.proyecto2018.linti.unlp.edu.ar/apiRest/api.php/instituciones/region-sanitaria/'.$cmd_params;
    $json = file_get_contents($url);
    $array = json_decode($json,true);
    $msg['text']  = 'Las instituciones disponibles son:'. PHP_EOL;
    foreach($array as $a){
        $msg['text']  .= 'Institucion: '.$a['nombre'].', Direccion: '.$a['direccion'].', Telefono: '. $a['telefono']. PHP_EOL;
    }
 
    default:
        $msg['text']  = 'Lo siento ' . $response['message']['from']['first_name'] . ', pero [' . $cmd . '] no es un comando válido.' . PHP_EOL;
        $msg['text'] .= 'Prueba /help para ver la lista de comandos disponibles';
        break;
}

//Descomentar para ver todo lo que envía telegram
/////////////$msg['text']= json_encode($response);



//Realizamos el envío
$url = 'https://api.telegram.org/bot638602251:AAGa0wlJbnEAqBMGPviutDkKulMxG0wHIaA/sendMessage';

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($msg)
    )
);
            
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
 
exit(0);
 
?>
