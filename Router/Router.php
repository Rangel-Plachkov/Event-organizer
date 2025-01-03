<?php
namespace Router;

use Exception\RoutingException;
use Support\ControllerFactory;

class Router
{

    private $request;
    private $routesRegistrator;
    private $target;
    private $controller;
    private $controllerClass = null;
    private $method;

    public function __construct(Request $request, Registrator $registrator)
    {
        $this->request = $request;
        $this->routesRegistrator = $registrator;
    }

    public function checkRequestMethod()
    {
        $routeMethod = $this->routesRegistrator->getRoutes()[$this->request->getUri()]["method"];
        $arr = explode('@', $routeMethod);
        if (!in_array($this->request->getRequestMethod(), $arr)) {
            throw new RoutingException("The request method is not valid...");
        }

        return true;
    }

    public function match()
    {
        if (!array_key_exists($this->request->getUri(),$this->routesRegistrator->getRoutes())){
            throw new RoutingException("The route doesn't exists...");
        }
        $this->setTarget($this->routesRegistrator->getRoutes()[$this->request->getUri()]["target"]);
    }

    public function setup()
    {
        $target = str_replace("[","", $this->getTarget());
        $target = str_replace("]", "", $target);
        $helperArr = explode("@",$target);
        //user@userRegistration

        $this->setController($helperArr[0]);
        $this->setMethod($helperArr[1]);
    }

    public function exec()
    {
        if ($this->checkRequestMethod()) {
            $controller = ControllerFactory::createController($this->getController());
            $method = $this->getMethod();
            $this->controllerClass = new $controller();
            if (!method_exists($this->controllerClass,$method)){
                throw new RoutingException("The controller method doesn't exists...");
            }
            $this->controllerClass->$method();
        }
    }

    public function start()
    {
        $this->match();
        $this->setup();
        $this->exec();
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function getControllerClass()
    {
        return $this->controllerClass;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($action)
    {
        $this->method = $action;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }
}
