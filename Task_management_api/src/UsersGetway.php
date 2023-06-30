<?php

class UsersGetway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getUserByApiKey(String $key): array | false
    {
        $sql = "SELECT * 
        FROM users 
        WHERE api_key = :api_key";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":api_key", $key, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByUserName(String $username): array | false
    {
        $sql = "SELECT *
                FROM users
                WHERE user_name = :user_name";
        
        $stmt = $this->conn->prepare($sql);
        $stmt ->bindValue(":user_name", $username);
        $stmt ->execute();

        return $stmt ->fetch(PDO::FETCH_ASSOC);
    }
}
