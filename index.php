<?php

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
}  catch (\Exception\SessionException $exception) {
    \http\Response::getInstance()->redirect(Router\Url::generateUrl('serverError'));
} catch (\Exception\RoutingException $exception) {
    \http\Response::getInstance()->redirect(Router\Url::generateUrl('pageNotFoundError'));
} catch (\Exception\DataBaseConnectionException $exception) {
    \http\Response::getInstance()->redirect(Router\Url::generateUrl('serverError'));
} catch (Throwable $exception) {
    dd($exception);
    \http\Response::getInstance()->redirect(Router\Url::generateUrl('unhandledError'));
} catch (\Exception\RoutingException $exception) {
    \http\Response::getInstance()->redirect(Router\Url::generateUrl('userNotFoundError'));
}

