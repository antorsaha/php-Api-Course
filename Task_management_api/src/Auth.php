<?php

class Auth
{
    private int $user_id;

    public function __construct(private UsersGetway $users_getway)
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
}
