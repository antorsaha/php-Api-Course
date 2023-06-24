<?php

use Dotenv\Dotenv;

require __DIR__ . "/vendor/autoload.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__) . "\Api Course\Task_management_api");
    $dotenv->load();

    $database = new Database(
        $_ENV["DB_HOST"],
        $_ENV["DB_NAME"],
        $_ENV["USER_NAME"]
    );

    $conn = $database->getConnection();

    //echo $_POST["user-name"]."<br>";
    //echo $_POST["password"];

    $get_sql = "SELECT * FROM users WHERE user_name = :user_name";
    $get_stmt = $conn->prepare($get_sql);

    $get_stmt->bindValue(":user_name", $_POST["user-name"]);
    $get_stmt->execute();

    $data = $get_stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!empty($data)) {
        //the data is exists need to check password to the database if match have to show the api key
        $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

        if($password_hash == $data["password_hash"]){
            echo "welcome! your api key is " . $data["api_key"];
        }else{
            echo "wrong password<br>";
            echo "entered password : ". $password_hash;
            echo "<br>database password: " . $data["password_hash"];

        }

    } else {

        $insert_sql = "INSERT INTO users (user_name, password_hash, api_key)
    VALUES (:user_name, :password_hash, :api_key)";

        $stmt = $conn->prepare($insert_sql);

        $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $api_key = bin2hex(random_bytes(16));

        $stmt->bindValue(":user_name", $_POST["user-name"], PDO::PARAM_STR);
        $stmt->bindValue(":password_hash", $password_hash, PDO::PARAM_STR);
        $stmt->bindValue(":api_key", $api_key, PDO::PARAM_STR);

        $stmt->execute();
        echo "Thank you for registering. your api key is " . $api_key;
    }
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
                    <input type="text" name="user-name" placeholder="User name" required>
                </div>
                <div class="row">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
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