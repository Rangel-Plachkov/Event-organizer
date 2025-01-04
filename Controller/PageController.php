<?php

namespace Controller;

use http\SessionHandler;
use Service\UserService;

class PageController extends AbstractController
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
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
        require_once 'View/templates/create_event.html';
    }

}

