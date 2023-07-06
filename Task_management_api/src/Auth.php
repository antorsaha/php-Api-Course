<?php

class Auth
{
    private int $user_id;

    public function __construct(private UsersGetway $users_getway,
                                private JWTCodec $codec)
    {
    }

    public function authApiByKey(): bool
    {
        if (empty($_SERVER["HTTP_X_API_KEY"])) {
            http_response_code(400);
            echo json_encode(["error" => true, "message" => "missing API key"]);
            return false;
        }

        $api_key = $_SERVER["HTTP_X_API_KEY"];

        $user = $this->users_getway->getUserByApiKey($api_key);
        if ($user === false) {
            http_response_code(401);
            echo json_encode(["error" => true, "message" => "invalid API key"]);
            return false;
        }

        $this->user_id = $user['id'];

        return true;
    }

    public function getUserId (){
        return $this->user_id;
    }

    public function authApiByAccessToken(): bool{
        $headers =  apache_request_headers();

        if(!preg_match("/^Bearer\s+(.*)$/",$headers["Authorization"] ,$matches)){
            http_response_code(400);
            echo json_encode(["message" => "incomplete Autherization header"]);
            return false;
        }

        //var_dump($matches[1]);
        /*$plain_text = base64_decode($matches[1]);

        if($plain_text === false){
            http_response_code(400);
            echo json_encode(["message" => "invalid auth header"]);
            return false;
        }

        $data  = json_decode($plain_text, true);

        if($data === null){
            http_response_code(400);
            echo json_encode(["message" => "invalid json"]);
            return false;
        }*/

        try{
            $data = $this ->codec->decode($matches[1]);
        }catch(Exception $e){
            http_response_code(400);
            echo json_encode(["message" => $e->getMessage()]);
            return false;
        }
        

        $this ->user_id = $data["sub"];
        return true;
    }
}
