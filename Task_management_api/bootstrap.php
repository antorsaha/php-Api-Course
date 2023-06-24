<?php


require dirname(__DIR__) . "/vendor/autoload.php";

use Dotenv\Dotenv;
$dotnenv = Dotenv::createImmutable(dirname(__DIR__) . "/Task_management_api");
$dotnenv->load();

//Set header for response type
header("Content-type: application/json; charset = UTF-8");

//Error handler
set_error_handler("ExecptionHandeler::handleError");
//exception handeler
set_exception_handler("ExecptionHandeler::handleException");