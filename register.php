<?php

use Dotenv\Dotenv;

require __DIR__ . "/vendor/autoload.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__) . "/Task_management_api");
    $dotenv->load();

    $database = new Database(
        $_ENV["DB_HOST"],
        $_ENV["DB_NAME"],
        $_ENV["DB_USER"]
    );

    $CONN = $database ->getConnection();

    echo $_POST["user name"];
    echo $_POST["password"];
}



?>


<!DOCTYPE html>
<!-- Created By CodingLab - www.codinglabweb.com -->
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form | CodingLab</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <div class="title"><span>Login Form</span></div>
            <form method="post">
                <div class="row">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="User name" required>
                </div>
                <div class="row">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Password" required>
                </div>
                <div class="pass"><a href="#">Forgot password?</a></div>
                <div class="row button">
                    <input type="submit" value="Login">
                </div>
                <!--<div class="signup-link">Not a member? <a href="#">Signup now</a></div>-->
            </form>
        </div>
    </div>

</body>

</html>