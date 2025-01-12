<?php

namespace Controller;

use http\SessionHandler;
use Service\UserService;
use Service\EventService;

class PageController extends AbstractController
{
    private $userService;
    private $eventService;
    public function __construct()
    {
        $this->userService = new UserService();
        $this->eventService = new EventService();
    }
    public function signUp()
    {
        require_once 'View/templates/signUpForm.html';
    }
    public function signIn()
    {
        require_once 'View/templates/signInForm.html';
    }
    public function edit()
    {
        $session = SessionHandler::getInstance();
        $this->userService->populateUser($session->getSessionValue('userId'));
        require_once 'View/templates/editProfile.phtml';
    }
    public function createEvent()
    {
        require_once 'View/templates/create_event_v2.html';
    }
    public function listEvents()
    {
        $events = $this->eventService->getAllEvents();

        require_once  'View/templates/event_list.phtml';
    }
}
