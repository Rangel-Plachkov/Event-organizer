<?php

namespace Controller;


class ErrorController extends AbstractController
{
    public function error404(){
        require_once 'View/templates/404.html';
    }

    public function error500()
    {
        require_once 'View/templates/500.html';
    }
    

    public function unhandledError()
    {
        require_once 'View/unhandledError.html';
    }
}