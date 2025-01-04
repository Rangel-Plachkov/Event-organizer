<?php

namespace Repository;

use DatabaseConfig;
use Entity\User;

class UserRepository extends BaseRepository
{
    public function create(User $user){
        $sql = "INSERT INTO Users (firstName , lastName , birthdate , email , username , password)
                    VALUES (:firstName , :lastName , :birthdate , :email , :username , :password)";
        $params = [
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'birthdate' => $user->getBirthdate(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'password' =>$user->getPassword()
        ];
        $this->executeQuery($sql, $params);
    }

    public function delete($id){
        $sql = "DELETE FROM Users WHERE id = :id";
        $params = [':id' => $id];
        $this->executeQuery($sql, $params);
    }

    public function findUserByEmail($email) {
        $sql = "SELECT * FROM Users WHERE email = :email";
        $params = [':email' => $email];
        return $this->fetchOne($sql, $params);
    }
    public function findUserByUsername($username) {
        $sql = "SELECT * FROM Users WHERE username = :username";
        $params = [':username' => $username];
        return $this->fetchOne($sql, $params);
    }
    public function findUserById($id) {
        $sql = "SELECT * FROM Users WHERE id = :id";
        $params = [':id' => $id];
        return $this->fetchOne($sql, $params);
    }
    public function findUserPasswordByUsername($username) {
        $sql = "SELECT password FROM Users WHERE username = :username";
        $params = [':username' => $username];
        $result=$this->fetchOne($sql, $params);
        return $result['password'] ?? null;
    }
    public function updateUser($user,$userId){
        $sql = "UPDATE Users SET 
            firstname = :firstname,
            lastname = :lastname,
            birthdate = :birthdate,
            email = :email,
            username = :username
        WHERE 
            id = :id
        ";

        $params = [
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstName(),
            'lastname' => $user->getLastName(),
            'username' => $user->getUsername(),
            'birthdate' => $user->getBirthdate(),
            'id' => $userId
        ];
        $this->executeQuery($sql, $params);
    }
    public function updateUserFully($user,$userId){
        $sql = "UPDATE Users SET 
            firstname = :firstname,
            lastname = :lastname,
            birthdate = :birthdate,
            email = :email,
            username = :username,
            password = :password
        WHERE 
            id = :id
        ";

        $params = [
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstName(),
            'lastname' => $user->getLastName(),
            'username' => $user->getUsername(),
            'birthdate' => $user->getBirthdate(),
            'password' => $user->getPassword(),
            'id' => $userId
        ];
        $this->executeQuery($sql, $params);
    }


    public function query(){

    }
}