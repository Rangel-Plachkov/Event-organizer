<?php
use \Router;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "autoLoader.php";
include "routes.php";
include "debuggerTool.php";

$request = Router\Request::getInstance();
$router  = new Router\Router($request, $routesRegistrator);

try {
    $router->start();
} catch (\Exception $e) {
    echo $e->getMessage();
}

