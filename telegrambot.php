<?php
  
$rawData = file_get_contents('php://input');
$response = json_decode($rawData, true);
$id_del_chat = $response['message']['chat']['id'];

$result[0]=$response['message']['text'];
if(strpos($result[0], ':') !== false){
    $result=explode(":",$response['message']['text']);
}
 
// Mensaje de respuesta
$msg = array();
$msg['chat_id'] = $response['message']['chat']['id'];
$msg['text'] = null;
$msg['disable_web_page_preview'] = true;
$msg['reply_to_message_id'] = (int)$response['message']['message_id'];
$msg['reply_markup'] = null;
 
// Comandos
switch ($result[0]) {
    case '/start':
        $msg['text']  = 'Hola ' . $response['message']['from']['first_name'] . PHP_EOL;
        $msg['text'] .= 'este es el bot del hospital Dr.Alejandro Korn para consultar la informacion sobre instituciones, para conocer los comandos validos envía /ayuda';
        $msg['reply_to_message_id'] = null;
        break;
 
    case '/ayuda':
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
    if(!empty($array)){
    $msg['text']  = 'Las instituciones disponibles son:'. PHP_EOL;
    foreach($array as $a){
        $msg['text']  .= 'Institucion: '.$a['nombre'].', Direccion: '.$a['direccion'].', Telefono: '. $a['telefono']. PHP_EOL;
    }
}else{
    $msg['text']  = 'No hay instituciones'. PHP_EOL;
}
     break;
 
    case '/instituciones-region-sanitaria':
    $url ='https://grupo12.proyecto2018.linti.unlp.edu.ar/apiRest/api.php/instituciones/region-sanitaria/'.$result[1];
    $json = file_get_contents($url);
    $array = json_decode($json,true);
    if(!empty($array)){
    $msg['text']  = 'Las instituciones disponibles son:'. PHP_EOL;
    foreach($array as $a){
        $msg['text']  .= 'Institucion: '.$a['nombre'].', Direccion: '.$a['direccion'].', Telefono: '. $a['telefono']. PHP_EOL;
    }
}else{
    $msg['text']  = 'No hay instituciones en esa region o la region no existe'. PHP_EOL;
}
      break;
    default:
        $msg['text']  = 'Lo siento ' . $response['message']['from']['first_name'] . ', pero [' . $cmd . '] no es un comando válido.' . PHP_EOL;
        $msg['text'] .= 'Prueba /help para ver la lista de comandos disponibles';
        break;
}

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
