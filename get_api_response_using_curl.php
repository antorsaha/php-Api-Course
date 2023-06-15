<?php


//Read data from api using curl
$random_user_api = "https://randomuser.me/api";

//Add headers to set metadata of the request like api key etc.
//Making a header 
$headers = [
    "Authentacation: Client_id xxxxxxxx"
];

//get response header into an array
$response_headers = [];

$header_callback = function($ch, $header) use (&$response_headers){
    $len = strlen($header);
    $parts = explode(":", $header, 2);

    if(count($parts) < 2){
         return $len;
    }
    $response_headers[$parts[0]] = trim($parts[1]);

    return $len;
       
};

$ch = curl_init();

//setting curlopt
//curl_setopt($ch, CURLOPT_URL, $random_user_api);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Or can set curlopt using setopt_array
curl_setopt_array($ch, [
    CURLOPT_URL => $random_user_api,
    CURLOPT_RETURNTRANSFER => true,

    //add the headers to the curlopt
    //CURLOPT_HTTPHEADER => $headers,

    //get metadata of response
    //CURLOPT_HEADER => true,
    CURLOPT_HEADERFUNCTION => $header_callback,
]);

//Executing curl and return a string
$response = curl_exec($ch);

$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
//$content_length = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

curl_close($ch);

//echo $status_code . "\n";
//echo $content_type . "\n";
//echo $content_length . "\n";

print_r($response_headers);
echo "\n\n";
echo $response;


?>