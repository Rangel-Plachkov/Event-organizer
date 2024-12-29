<?php
namespace http;

use Exception\SessionException;

class SessionHandler
{
    private static $instance = null;

    private $sessionId;

    private function __construct()
    {
        if (session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $this->sessionId = session_id();
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setSessionValue(
        $key,
        $value
    ) {
        $_SESSION[$key] = $value;
    }

    public function getSessionValue(string $key)
    {
        if (!array_key_exists(
            $key,
            $_SESSION
        )) {
            return null;
        }

        return $_SESSION[$key];
    }

    public function sessionArrUnset()
    {
        if (empty($_SESSION)) {
            throw  new SessionException("The session array is empty!");
        }
        session_unset();
    }

    public function sessionDestroy()
    {
        if (session_status() === PHP_SESSION_NONE) {
            throw  new SessionException("There is no session for destroying!");
        }
        session_destroy();
        session_start();
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }
}