<?php
namespace Router;

use Exception\RoutingException;

class Url
{
    public static function generateUrl(string $name)
    {
        $registrator = Registrator::getInstance();
        $routes = $registrator->getNameUrlMatch();
        if (!array_key_exists($name, $routes)){
            throw new \Exception("Not existing url...");
        }

        if ($name == "indexPage" ) {
            $url = $routes[$name]['url'];
            $cleanUrl = preg_replace('/\/+$/', '/', $url);
            return $cleanUrl;
        }

        return $routes[$name]['url'];
    }

    public static function generateUri(string $name)
    {
        $registrator = Registrator::getInstance();
        $routes = $registrator->getNameUriMatch();
        if (!array_key_exists($name, $routes)){
            throw new \Exception("Not existing uri...");
        }
        return $routes[$name]['uri'];
    }
}