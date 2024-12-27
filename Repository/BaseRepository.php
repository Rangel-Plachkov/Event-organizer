<?php

namespace Repository;

use mysql_xdevapi\Statement;

abstract class BaseRepository
{
    protected $connection;

    /**
     * @param $connection
     */
    public function __construct($connection)
    {
        $this->connection=\DatabaseConfig::getConnection();
    }
    protected function executeQuery($sql, $params = []){
        $statement = $this->connection->prepare($sql);
        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        return $statement;
    }
    protected function fetchOne($sql, $params = []) {
        $statement = $this->executeQuery($sql, $params);
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }
    protected function fetchAll($sql, $params = [])
    {
        $statement = $this->executeQuery($sql, $params);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

}