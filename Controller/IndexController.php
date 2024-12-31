<?php

namespace Controller;

use http\SessionHandler;

class IndexController extends AbstractController
{
    public function index() {
        $session = SessionHandler::getInstance();
        $isLogged = $session->getSessionValue('logged');
        if($isLogged) {
            require_once 'View/templates/dashboard.phtml';
        } else {
            require_once 'View/templates/mainPage.phtml';
        }
    }

}