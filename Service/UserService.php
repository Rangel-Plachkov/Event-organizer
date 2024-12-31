<?php

namespace Service;

use http\Client\Curl\User;
use http\SessionHandler;
use Repository\UserRepository;

class UserService
{
    private $userRepository;

    /**
     * @param $userRepository
     */
    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function createUser(\Entity\User $user){
        if ($this->isEmailTaken($user->getEmail())) {
            echo "Email is taken!";
            die();
        }
        if($this->isUsernameTaken($user->getUsername())){
            echo "Username is taken!";
            die();
        }
        $password=$user->getPassword();
        $hashedPassword=password_hash($password, PASSWORD_DEFAULT);
        $user->setPassword($hashedPassword);
        $this->userRepository->create($user);
    }

    public function isUsernameTaken($username): bool{
        return !($this->userRepository->findUserByUsername($username) == null);
    }

    public function isEmailTaken($email): bool
    {
        return !($this->userRepository->findUserByEmail($email) == null);
    }

    public function login($username, $password){
        $session = SessionHandler::getInstance();
        if($this->isUsernameTaken($username)){
            $user = $this->userRepository->findUserByUsername($username);
            if(password_verify($password, $user['password'])){
                $session->setSessionValue('logged', 1);
                $session->setSessionValue('error', 0);
                $session->setSessionValue('username' , $username);
                $session->setSessionValue('fullName' , $user['firstname'] . ' ' . $user['lastname']);
                return true;
            }
        } else {
            $session->setSessionValue('logged', 0);
            $session->setSessionValue('error', 1);
            $session->setSessionValue('errorMsg', 'Username and password do not match');
            return false;
        }
    }

    public function logout(){
        $session = SessionHandler::getInstance();
        $session->sessionDestroy();
    }
}