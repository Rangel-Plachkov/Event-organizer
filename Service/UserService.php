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
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
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
    public function populateUser($user_id){
        $user = $this->userRepository->findUserById($user_id);
        $_SESSION['email'] = $user['email'];
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['lastname'] = $user['lastname'];
        $_SESSION['birthdate'] = $user['birthdate'];
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
                $session->setSessionValue('userId' , $user['id']);

                return true;
            }
        } else {
            $session->setSessionValue('logged', 0);
            $session->setSessionValue('error', 1);
            $session->setSessionValue('errorMsg', 'Username and password do not match');
            return false;
        }
    }

    private function validateEditUser(){

    }

    public function deleteUser(){
        $session=$session = SessionHandler::getInstance();
        $id=$session->getSessionValue('userId');
        $this->userRepository->delete($id);
        $session->sessionDestroy();
    }

    public function logout(){
        $session = SessionHandler::getInstance();
        $session->sessionDestroy();
    }



    public function editAcc(\Entity\User $user, $old_password){
        $session = SessionHandler::getInstance();
        $id = $session->getSessionValue('userId');
        $email =$session->getSessionValue('email');
        $username = $session->getSessionValue('username');
        if($email != $user->getEmail() && $this->isEmailTaken($user->getEmail())){
            echo "Email is taken!";
            return;
        }

        if($username != $user->getUsername() && $this->isUsernameTaken($user->getUsername())){
            echo "Username is taken!";
            return;
        }

        if($old_password == null){
                $this->userRepository->updateUser($user,$id);
            $session->setSessionValue('update_success', "User updated!");
        } else {
            $passwordHash = $this->userRepository->findUserPasswordByUsername($username);
            if(password_verify($old_password, $passwordHash)){
                $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));
                $this->userRepository->updateUserFully($user,$id);
                $session->setSessionValue('update_success', "User updated!");
            } else {
                echo "Wrong password!";
                return ;
            }
        }
    }

    public function getUserByUsername($username){
        return $this->userRepository->findUserByUsername($username);
    }




    public function follow($usernameToFollow): void
    {
        $followerId = $_SESSION['userId'];
        $followingUser = $this->userRepository->findUserByUsername($usernameToFollow);

        if (!$followingUser) {
            echo "User to follow does not exist.";
            return;
        }

        $followingId = $followingUser['id'];

        if ($followerId === $followingId) {
            echo "You cannot follow yourself.";
            return;
        }

        if ($this->userRepository->isFollowing($followerId, $followingId)) {
            echo "You are already following this user.";
            return;
        }

        $this->userRepository->followUser($followerId, $followingId);
    }

    public function unfollow($usernameToUnfollow): void
    {
        $followerId = $_SESSION['userId'];
        $followingUser = $this->userRepository->findUserByUsername($usernameToUnfollow);

        if (!$followingUser) {
            echo "User to unfollow does not exist.";
            return;
        }

        $followingId = $followingUser['id'];

        if (!$this->userRepository->isFollowing($followerId, $followingId)) {
            echo "You are not following this user.";
            return;
        }

        $this->userRepository->unfollowUser($followerId, $followingId);
    }

    public function isFollowing($username): bool
    {

        $followerId = $_SESSION['userId'];
        $followingUser = $this->userRepository->findUserByUsername($username);

        if (!$followingUser) {
            echo "User to check does not exist.";
            return false;
        }

        $followingId = $followingUser['id'];

        return $this->userRepository->isFollowing($followerId, $followingId);
    }


    public function getFollowing($username): array
    {
        $user = $this->userRepository->findUserByUsername($username);

        if (!$user) {
            echo "User does not exist.";
            return [];
        }

        return $this->userRepository->getFollowedUsers($user['id']);
    }

    public function getFollowers($username): array
    {
        $user = $this->userRepository->findUserByUsername($username);

        if (!$user) {
            echo "User does not exist.";
            return [];
        }

        return $this->userRepository->getFollowers($user['id']);
    }






    public function followFromTo($followerId, $followingId): void
    {
        if ($followerId === $followingId) {
            echo "You cannot follow yourself.";
            return;
        }

        if ($this->userRepository->isFollowing($followerId, $followingId)) {
            echo "You are already following this user.";
            return;
        }

        $this->userRepository->followUser($followerId, $followingId);
        echo "User followed successfully!";
    }

    public function unfollowFromTo($followerId, $followingId): void
    {
        if (!$this->userRepository->isFollowing($followerId, $followingId)) {
            echo "You are not following this user.";
            return;
        }

        $this->userRepository->unfollowUser($followerId, $followingId);
        echo "User unfollowed successfully!";
    }

}