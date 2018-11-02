<?php
include_once "../vendor/autoload.php";
include_once "repositorio.php";

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



$app = new \Slim\App();

$app->get('/instituciones', function ($request, $response, $args)   {
	return $response->withJson(Repositorio::getInstance()->todos(), 200);
});

$app->get('/instituciones/{id}', function ($request, $response, $args) {
	return $response->withJson(Repositorio::getInstance()->institucion_id($args['id']), 200);
});

$app->get('/instituciones/region-sanitaria/{region-sanitaria}', function ($request, $response, $args) {
	return $response->withJson(Repositorio::getInstance()->region_id($args['region-sanitaria']), 200);
});

$app->run();

?>