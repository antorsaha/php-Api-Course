<?php

declare(strict_types=1);
require (__DIR__) . "/bootstrap.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo json_encode(["message" => "Allow Post only"]);
    header("Allow: post");
    exit;
}

$data = (array) json_decode(file_get_contents("php://input"), true);

if (
    !array_key_exists("username", $data) ||
    !array_key_exists("password", $data)
) {
    http_response_code(400);
    echo json_encode(["message" => "Missing login credentials"]);
    exit;
}

$database = new Database();
$usersGetway = new UsersGetway($database);
$user = $usersGetway->getUserByUserName($data["username"]);

if ($user === false) {
    http_response_code(401);
    echo json_encode(["message" => "Invalid authentication"]);
    exit;
}

if (!password_verify($data["password"], $user["password_hash"])) {
    http_response_code(401);
    echo json_encode(["message" => "Invalid authentication"]);
    exit;
}

$payload = [
    "id" => $user["id"],
    "name" => $user["user_name"]
];

$accessToken = base64_encode(json_encode($payload));


echo json_encode(["access token" => $accessToken]);
