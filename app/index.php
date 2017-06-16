<?php

session_start();

ini_set("display_errors", 1);
ini_set("error_reporting", E_ALL);

require 'config.php';
require 'DB.php';
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array('debug' => false));
$app->contentType("application/json");
$app->error(function ( Exception $e = null) use ($app) {
    echo json_encode(array(
        "error" => array(
            "text" => $e->getMessage()
        )
    ));
});

//GET pode possuir um parametro na URL
$app->get('/:controller/:action(/:parameter)', function ($controller, $action, $parameter = null) use($app) {
    include_once "classes/{$controller}.php";
    $classe = new $controller();
    $retorno = call_user_func_array(array($classe, "get_" . $action), array($parameter));
    echo json_encode(array(
        "result" => $retorno
    ));
});

//POST não possui parâmetros na URL, e sim na requisição
$app->post('/:controller/:action', function ($controller, $action) use ($app) {
    $request = json_decode(\Slim\Slim::getInstance()->request()->getBody());
    include_once "classes/{$controller}.php";
    $classe = new $controller();
    
    if (!$request) {
        $request = (object) $app->request()->params();
    }
    
    $retorno = call_user_func_array(array($classe, "post_" . $action), array($request));
    echo json_encode(array(
        "result" => $retorno
    ));
});

//$app->post('/', 'uploadFile');

$app->run();