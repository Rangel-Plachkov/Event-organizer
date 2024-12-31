<?php

namespace Controller;

use Entity\User;
use http\SessionHandler;
use Router\Url;
use Service\UserService;

class UserController extends AbstractController
{
    private $userService;

    /**
     * @param $userService
     */
    public function __construct()
    {
        $this->userService=new UserService();
    }

    public function createAcc(){
        $firstName = $_POST['firstname'];
        $lastName = $_POST['lastname'];
        $birthdate = $_POST['birthdate'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm-password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Невалиден имейл адрес!";
            return;
        }

        if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password)) {
            echo "Паролата трябва да съдържа поне една буква и една цифра!";
            return;
        }

        if ($password !== $confirmPassword) {
            die();
        }
        $user=new User(null , $firstName, $lastName, $birthdate, $email, $username, $password);
        $this->userService->createUser($user);
        header("Location:". Url::generateUrl('indexPage'));
    }

    public function login(){
        $username=$_POST['username'];
        $password=$_POST['password'];
        if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password)) {
            echo "Паролата трябва да съдържа поне една буква и една цифра!";
        }
        $this->userService->login($username,$password);
        header("Location:". Url::generateUrl('indexPage'));
    }

    public function logout(){
        $this->userService->logout();
        header("Location:". Url::generateUrl('indexPage'));
    }
}