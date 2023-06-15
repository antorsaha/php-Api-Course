<?php

class ExecptionHandeler
{

    public static function handleError(
        int $errorno,
        string $errorstr,
        string $errorfile,
        int $errorline
    ) : void{
        throw new ErrorException($errorstr, 0, $errorno, $errorfile, $errorline);
    }

    public static function handleException(Throwable $exception){
        http_response_code(500);
        echo json_encode([
            "code" => $exception->getCode(),
            "message" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine()
        ]);
    }
}