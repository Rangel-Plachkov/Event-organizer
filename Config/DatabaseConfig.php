<?php

namespace Config;

use Exception\DataBaseConnectionException;
use util\Constants;

class DatabaseConfig
{
    protected static $conn = NULL;

    private function __construct(){ }

    public static function init_conn(){
        try{
            self::$conn = new \PDO("mysql:host=" . Constants::DB_HOST . ":" . Constants::DB_PORT . ";dbname=" .
                Constants::DB_NAME, Constants::DB_USER, Constants::DB_PASS);
            self::$conn->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        }catch (\PDOException $exception){
            throw new DataBaseConnectionException( "We can't init a connection..." . $exception->getMessage());
        }
    }

    public static function getConnection() {
        if (self::$conn === NULL) {
            self::init_conn();
        }
        return self::$conn;
    }
}