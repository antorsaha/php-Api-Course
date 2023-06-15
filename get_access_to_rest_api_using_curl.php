<?php

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => "https://api.github.com/gists",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERAGENT => "antorsaha"
]);

$response = curl_exec($ch);

echo $response;

curl_close($ch);
