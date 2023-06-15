<?php
class TaskController
{


    public function __construct(private TaskGetway $taskGetway)
    {
    }

    public function processRequest(string $method, ?string $id): void
    {

        if ($id === null) {
            switch ($method) {
                case "GET":
                    //Get all data from the database
                    $responseArray = $this->taskGetway->getAllTasks();
                    if (!empty($responseArray))
                        echo json_encode(["error" => "false", "data" => $responseArray]);
                    //echo json_encode($responseArray);
                    else
                        echo json_encode(["error" => "true"]);
                    break;
                case "POST":
                    //create a new task
                    $data = (array) json_decode(file_get_contents("php://input"), true);

                    $errors = $this->getValidationErrors($data);
                    if (!empty($errors)) {

                        $this->respondUnprocessableEntity($errors);
                        return;
                    }

                    $id = $this->taskGetway->create($data);
                    $this->respondCreateSuccessfully($id);

                    break;
                default:

                    $this->respondMethodNotAllowed("GET, POST");
            }
        } else {

            $task = $this->taskGetway->getTask(id: $id);
            if ($task === false) {
                $this->respondNotFound($id);
                return;
            }

            switch ($method) {
                case "GET":
                    echo json_encode($task);
                    break;

                case "PATCH":

                    $data = (array) json_decode(file_get_contents("php://input"), true);

                    $errors = $this->getValidationErrors($data, false);
                    if (!empty($errors)) {

                        $this->respondUnprocessableEntity($errors);
                        return;
                    }

                    $rows = $this->taskGetway->update($id, $data);
                    echo json_encode(["message" => "Task updated", "rows" => $rows]);
                    break;

                case "DELETE":
                    $rows = $this->taskGetway->delete($id);
                    echo json_encode(["message" => "Task deleted", "rows" => $rows]);

                    break;

                default:
                    $this->respondMethodNotAllowed("GET, PATCH, DELETE");
            }
        }
    }

    private function respondMethodNotAllowed(String $allowed_methods): void
    {
        header("allow: $allowed_methods");
        http_response_code(405);
        echo json_encode(["error" => true, "message" => "allow only $allowed_methods"]);
    }

    private function respondNotFound(String $id): void
    {
        http_response_code(404);
        echo json_encode(["message" => "Task with id $id not found", "error" => true]);
    }
    private function respondCreateSuccessfully(string $id): void
    {
        http_response_code(201);
        echo json_encode(["message" => "Task created", "id" => $id]);
    }

    private function respondUnprocessableEntity(array $errors): void
    {
        //unprocessable entity response code
        http_response_code(422);
        echo json_encode(["errors" => $errors]);
    }

    private function getValidationErrors(array $data, $is_new = true): array
    {
        $errors = [];
        if ($is_new && empty($data["name"])) {
            $errors[] = "name is required";
        }
        if (!empty($data["prority"])) {
            if (filter_var($data["prority"], FILTER_VALIDATE_INT) === false) {
                $errors[] = "prority must be integer";
            }
        }

        return $errors;
    }















    //Test Database connection
    private function databaseConnection()
    {
        $serverName = "localhost";
        $userName = "user";
        //$password = "hello_world";
        $databaseName = "task_management_api_database";

        try {
            //$connection = new PDO("mysql:host = $serverName; dbname= $databaseName", $userName);
            $connection = new PDO("mysql:host = $serverName; dbname=$databaseName", $userName);

            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo json_encode([
                "message" => "connection successfull"
            ]);

            $createDatabase = "CREATE DATABASE IF NOT EXISTS $databaseName";

            if ($connection->query($createDatabase) == true) {
                echo json_encode([
                    "message" => "database create successfull"
                ]);


                //table creation

                //$aa = "mysql:host=localhost;port=3307;dbname=". $databaseName;
                //$connection = new PDO("mysql:host = $serverName; dbname= $databaseName", $userName);

                /*echo json_encode([
                    "message" => "connection2 successfull"
                ]);*/


                /*$createTableQuerySql = "CREATE TABLE IF NOT EXISTS task (id INT NOT NULL AUTO_INCREAMENT,
                name VARCHAR(128) NOT NULL,
                prority INT DEFAULT NULL,
                is_completed BOOLEAN NOT NULL DEFAULT FALSE
                )";
        
                if($connection -> exec($createTableQuerySql) === true){
                    echo json_encode([
                        "message" => "table create successfull"
                    ]);
        
                }else{
                    echo json_encode([
                        "error" => "table create error"
                    ]);
        
                }*/


                $sql = "CREATE TABLE IF NOT EXISTS task (id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
                name VARCHAR(128) NOT NULL,
                prority INT DEFAULT NULL,
                is_completed BOOLEAN NOT NULL DEFAULT FALSE
                )";

                // use exec() because no results are returned
                $connection->exec($sql);
                echo "Table task created successfully";
            } else {
                echo json_encode([
                    "error" => "Database creation error",
                ]);
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
