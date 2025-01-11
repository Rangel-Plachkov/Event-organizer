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
    public function viewProfile(){
        //$username = $_GET['username'] ?? null;
        $users = [
            1 => ['firstName' => 'John', 'lastName' => 'Doe', 'birthdate' => '1990-01-01'],
            2 => ['firstName' => 'Jane', 'lastName' => 'Smith', 'birthdate' => '1995-05-15']
        ];

        $userData = $users[1];
        require_once 'View/templates/viewProfile.phtml';
    }

}

