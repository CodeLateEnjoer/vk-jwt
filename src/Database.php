<?php

class Database
{
    private PDO $connection;

    public function __construct()
    {

        $this->connection = new PDO(
            'mysql:host=db;dbname=mydb',
            'root',
            'secret'
        );
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
