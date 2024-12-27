<?php

namespace Repository;

use DatabaseConfig;

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

    public function update(){

    }

    public function delete(){

    }

    public function findUserByEmail($email) {
        $sql = "SELECT * FROM Users WHERE email = :email";
        $params = [':email' => $email];
        return $this->fetchOne($sql, $params);
    }


    public function query(){

    }
}