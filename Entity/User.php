<?php

namespace Entity;

use Exception;

class User
{
    private $id;
    private $firstName;
    private $lastName;
    private $birthDate;
    private $email;
    private $username;
    private $password;
    private $following;

    /**
     * @param $id
     * @param $firstName
     * @param $lastName
     * @param $birthDate
     * @param $email
     * @param $username
     * @param $password
     */
    public function __construct($id, $firstName, $lastName, $birthDate, $email, $username, $password)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthDate = $birthDate;
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->following = [];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param mixed $birthDate
     */
    public function setBirthDate($birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * Check if the user is following another user
     *
     * @param User $user
     * @return bool
     */
    public function isFollowing(User $user): bool
    {
        return in_array($user, $this->following, true);
    }


    /**
     * Follow a user
     *
     * @param User $user
     * @throws Exception if the user tries to follow himself
     */
    public function follow(User $user): void
    {
        if ($user->getId() === $this->id) {
            throw new Exception("You cannot follow yourself");
        }
        if(!$user->isFollowing($this)){
            $this->following[] = $user;
        }

    }


    /**
     * Unfollow a user
     *
     * @param User $user
     */
    public function unfollow(User $user): void
    {
        $key = array_search($user, $this->following, true);
        if ($key !== false) {
            unset($this->following[$key]);
        }
    }

    /**
     * Get the users that the current user is following
     *
     * @return array
     */
    public function getFollowing(): array
    {
        return $this->following;
    }

}