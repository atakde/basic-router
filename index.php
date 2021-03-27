<?php

define("ROOTFOLDER", dirname($_SERVER['SCRIPT_NAME']));

include_once(__DIR__ . "/Router.php");

$requestURI = htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
$requestMethod = htmlentities($_SERVER['REQUEST_METHOD'], ENT_QUOTES, 'UTF-8');

$router = new Router(substr($requestURI, strlen(\ROOTFOLDER)), $requestMethod);

$router->get('/', function () {
    Router::sendResponse(["message" => "homepage"], 200);
});

$router->get('/test', function () {
    Router::sendResponse(["message" => "test"], 200);
});

$router->get('/test/([\d]+)', function ($id) {
    Router::sendResponse(["id" => intval($id), "message" => "Successfully fetched!"], 200);
});

$router->post('/test', function () {
    Router::sendResponse(["message" => "postExample"], 200);
});

$router->group('/movies', function () use ($router) {
    $router->get('/', function () {
        Router::sendResponse(["message" => "movies"], 200);
    });
    $router->get('/([\d]+)', function ($id) {
        Router::sendResponse(["id" => intval($id), "message" => "Successfully fetched!"], 200);
    });
});