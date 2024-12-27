<?php

class DatabaseConfig
{
    protected static $conn = NULL;

    private function __construct(){ }

    public static function init_conn(){
        try{
            self::$conn = new \PDO("mysql:host=" . DB_HOST . ":" . DB_PORT . ";dbname=" .
                DB_NAME, DB_USER, DB_PASS);
            self::$conn->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        }catch (\PDOException $exception){
            echo "We can't init a connection..." . $exception->getMessage();
        }
    }

    public static function getConnection() {
        if (self::$conn === NULL) {
            self::init_conn();
        }
        return self::$conn;
    }
}