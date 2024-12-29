<?php
namespace Router;

class Registrator
{
    private static $instance;

    private $routes = [];
    private $nameUriMatch = [];
    private $nameUrlMatch = [];

    private function __construct() {}

    public static function getInstance()
    {
        if (empty(self::$instance)){
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function map($method, $target, $uri, $name, $params = [])
    {
       $this->routes[$uri] = [
           "method" => $method,
           "target" => $target,
           "name" => $name,
           "params" => $params
       ];

       $this->nameUriMatch[$name] = [
          "uri" => $uri
       ];

       $this->nameUrlMatch[$name] = [
         "url" => Request::DOMAIN . "/" . $uri
       ];
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }

    public function getNameUriMatch()
    {
        return $this->nameUriMatch;
    }

    public function setNameUriMatch($nameUriMatch)
    {
        $this->nameUriMatch = $nameUriMatch;
    }

    public function getNameUrlMatch()
    {
        return $this->nameUrlMatch;
    }

    public function setNameUrlMatch($nameUrlMatch)
    {
        $this->nameUrlMatch = $nameUrlMatch;
    }
}
