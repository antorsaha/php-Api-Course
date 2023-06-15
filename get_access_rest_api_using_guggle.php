<?php

require __DIR__ . "/vendor/autoload.php";

$client = new GuzzleHttp\Client;

$response = $client->request("GET", "https://api.github.com/antorsaha/repo", [
    "headers" => [
        "Authorization" => "token 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",
        "User-Agent" => "antorsaha"
    ]
]);

echo $response -> getStatusCode(). "\n";
echo $response -> getHeader("Content-type")[0]. "\n\n";
echo $response ->getBody();