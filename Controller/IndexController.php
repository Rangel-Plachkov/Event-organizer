<?php

namespace Controller;

use http\SessionHandler;
use Service\EventService;

class IndexController extends AbstractController
{
    private $eventService;

    public function __construct()
    {
        $this->eventService = new EventService();
    }
    public function index() {
        $session = SessionHandler::getInstance();
        $isLogged = $session->getSessionValue('logged');
        if($isLogged) {
            $this->eventService->loadMyEvents();
            require_once 'View/templates/dashboard.phtml';
        } else {
            require_once 'View/templates/mainPage.phtml';
        }
    }

}