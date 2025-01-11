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
        $this->userService = new UserService();
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
    public function editAcc(){
        $username=$_POST['username'];
        $firstname=$_POST['firstname'];
        $lastname=$_POST['lastname'];
        $birthdate=$_POST['birthdate'];
        $email=$_POST['email'];
        $currentPassword=$_POST['password'];
        $newPassword=$_POST['newpassword'];
        $confirmPassword=$_POST['confirmpassword'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Not valid email address";
            return;
        }
        if($newPassword == null){
            $user=new User(null , $firstname, $lastname, $birthdate, $email, $username, null);
            $this->userService->editAcc($user ,null);
        }  else {
            if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $newPassword)) {
                echo "New password must contain at least one letter and one number!";
                return;
            }
            if ($newPassword !== $confirmPassword) {
                echo "Passwords do not match!";
                return;
            }
            $user=new User(null , $firstname, $lastname, $birthdate, $email, $username, $newPassword);
            $this->userService->editAcc($user ,$currentPassword);
        }
        header("Location:". Url::generateUrl('editPage'));
    }

    public function deleteAcc(){
        $this->userService->deleteUser();
        header("Location:". Url::generateUrl('indexPage'));
    }

    public function searchUser(){
        $searchedUser=$_POST['searchedUser'];
        $FOUND_USERS=$this->userService->getUserByUsername($searchedUser);
        require_once 'View/templates/viewProfile.phtml';
    }

}