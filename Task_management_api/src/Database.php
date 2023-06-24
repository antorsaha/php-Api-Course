<?php

class Database
{
    /*public function __construct(
        private String $host,
        private String $databaseName,
        private String $userName
    ) {
    }*/
    private ?PDO $conn = null;

    public function getConnection(): PDO
    {
        //echo "mysql:host = {$this->host}; dbname = {$this->databaseName};charset=utf8; username";
        //$dsn = "mysql:host = {$this->host}; dbname = {$this->databaseName};charset=utf8";

        if ($this->conn === null) {
            $this->conn =  new PDO("mysql:host=localhost;dbname=" . $_ENV["DB_NAME"], $_ENV["USER_NAME"], null, [
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false
            ]);
        }

        return $this->conn;
    }
}
