<?php

require_once(__DIR__ . "/Router.php");

$router = new Router(substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME']))), $_SERVER['REQUEST_METHOD']);

$router->get('/', function () use ($router) {
    $router->sendResponse(["message" => "homepage"], 200);
});

$router->get('/test', function () use ($router) {
    $router->sendResponse(["message" => "test"], 200);
});

$router->get('/test/([\d]+)', function ($id) use ($router) {
    $router->sendResponse(["id" => intval($id), "message" => "Successfully fetched!"], 200);
});

$router->post('/test', function () use ($router) {
    $router->sendResponse(["message" => "postExample"], 200);
});
