<?php

namespace Controller;

use Entity\User;
use Service\UserService;

class UserController
{
    private $userService;

    /**
     * @param $userService
     */
    public function __construct()
    {
        $this->userService=new UserService();
    }
    public function createUser(){
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $birthdate = $_POST['birthdate'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Невалиден имейл адрес!";
            return;
        }

        if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password)) {
            echo "Паролата трябва да съдържа поне една буква и една цифра!";
            return;
        }

        if ($password !== $confirmPassword) {
            echo "Паролите не съвпадат";
        }
        $user=new User($firstName,$lastName,$birthdate,$email,$username,$password);
        $this->userService->createUser($user);

    }
}