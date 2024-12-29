<?php

namespace Service;

use http\Client\Curl\User;
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
//        if ($this->isEmailTaken($user->getEmail())) {
//            echo "Имейлът вече е използван!";
//            return;
//        }
        $password=$user->getPassword();
        password_hash($password, PASSWORD_DEFAULT);
        $user->setPassword($password);
        $this->userRepository->create($user);
    }
    public function isUsernameTaken($username){

    }

}