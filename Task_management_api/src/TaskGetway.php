<?php

class TaskGetway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAllTasks(): array
    {

        $sql = "SELECT * FROM task ORDER BY name";

        $smt = $this->conn->query($sql);
        //return $smt ->fetchAll(PDO::FETCH_ASSOC);


        $data = [];
        while ($row = $smt->fetch(PDO::FETCH_ASSOC)) {
            $row['is_completed'] = (bool) $row['is_completed'];
            array_push($data, $row);
        }
        return $data;
    }

    public function getTask(String $id): array | false
    {
        $sql = "SELECT * 
        FROM task 
        WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data != false) {
            $data['is_completed'] = (bool) $data['is_completed'];
        }
        return $data;
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO task (name, prority, is_completed) 
                VALUES (:name, :prority, :is_completed)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        if (empty($data["prority"])) {
            $stmt->bindValue(":prority", null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(":prority", $data["prority"], PDO::PARAM_INT);
        }

        $stmt->bindValue(":is_completed", $data["is_completed"] ?? false, PDO::PARAM_BOOL);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function update(string $id, array $data) : int
    {
        $fields = [];

        if (array_key_exists("name", $data)) {
            $fields["name"] = [
                $data["name"],
                PDO::PARAM_STR
            ];
        }
        if (array_key_exists("prority", $data)) {
            $fields["prority"] = [
                $data["prority"],
                $data["prority"] === null ? PDO::PARAM_NULL : PDO::PARAM_INT
            ];
        }
        if (array_key_exists("is_completed", $data)) {
            $fields["is_completed"] = [
                $data["is_completed"],
                PDO::PARAM_BOOL
            ];
        }


        if (empty($fields)) {
            return 0;
        } else {
            $sets = array_map(function ($value) {
                return "$value = :$value";
            }, array_keys($fields));

            $sql = "UPDATE task"
                . " SET " . implode(", ", $sets)
                . " WHERE id = :id";

            
                $stmt = $this->conn->prepare($sql);

                $stmt->bindValue(":id", $id, PDO::PARAM_INT);

                foreach($fields as $name => $values){
                    $stmt->bindValue(":$name", $values[0], $values[1]);
                }

                $stmt->execute();

                return $stmt->rowCount();
                
        }
    }

    public function delete(string $id) : int{
        $sql = "DELETE FROM task WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt ->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->rowCount();
        
    }
}
