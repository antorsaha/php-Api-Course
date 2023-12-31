<?php

declare(strict_types=1);
require __DIR__ . "/bootstrap.php";

use GuzzleHttp\Psr7\Message;

//ini_set("display_errors", "On");


//get page url
//this will print full request url including query string
//echo $_SERVER["REQUEST_URI"];

//perse url to get without query string
//echo "<br>without query String<br>";
//echo parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

//devide paths to get specific element
//echo "<br>print path elements<br>";
$full_path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$parts = explode("/", $full_path);
//print_r(($parts));

//finding the request method
//echo "<br>".$_SERVER["REQUEST_METHOD"];

//validation of url http://localhost/api%20course/Task_management_api/tasks/152
$resouce = $parts[3];
$id = $parts[4] ?? null;
if ($resouce != "tasks") {
    //header("{$_SERVER['SERVER_PROTOCOL']} 404 not found");
    http_response_code(404);
    exit;
}

//echo "<br>".$_SERVER["REQUEST_METHOD"];


//require dirname(__DIR__) . "/Task_management_api/src/TaskController.php";

//$database = new Database();
//$database ->getConnection();

//echo $_ENV["DB_HOST"]." ". $_ENV["DB_NAME"]." " . $_ENV["USER_NAME"];


/*Test

try {
    $conn = new PDO("mysql:host=localhost;dbname=".$_ENV["DB_NAME"], $_ENV["USER_NAME"], null);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$sql = "SELECT * FROM task ORDER BY name";

$stmt = $conn->query($sql);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

*/


$database = new Database();

$users_getway = new UsersGetway($database);

$codec = new JWTCodec($_ENV["SECRET_KEY"]);

$auth = new Auth($users_getway, $codec);
if (!$auth->authApiByAccessToken()) {
    exit;
}

$user_id = $auth -> getUserId();



$conn = $database->getConnection();

$database_creation_sql = "CREATE DATABASE IF NOT EXISTS " . $_ENV["DB_NAME"];
$conn->exec($database_creation_sql);

//$sql = "SELECT * FROM task ORDER BY name";
//$stmt = $conn->query($sql);
//echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

$getway = new TaskGetway($database);

$controller = new TaskController($getway, $user_id);
$controller->processRequest(method: $_SERVER["REQUEST_METHOD"], id: $id);
