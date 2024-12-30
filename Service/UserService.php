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
        if($this->isUsernameTaken($username)){
            $hashedPassword=$this->userRepository->findUserPasswordByUsername($username);
            if(password_verify($password,$hashedPassword)){
                echo "Login successful!";
            }
        }
        else{
            echo "User doesnt exist!";
            die();
        }
    }

}