<?php
namespace Support;

class ControllerFactory
{
    public static function createController($name)
    {
        $controllerName = "\\Controller\\" . ucfirst($name) . "Controller";
        if (!class_exists($controllerName)) {
            throw new \Exception("The controller class doesn't exists...");
        }

        return  new $controllerName();
    }
}