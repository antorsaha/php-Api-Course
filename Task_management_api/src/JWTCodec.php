<?php

class JWTCodec
{
    public function __construct(private string $key)
    {
    }
    public function encode(array $payload): string
    {

        $header = json_encode([
            "typ" => "JWT",
            "alg" => "HS256"
        ]);
        $header = $this->base64UrlEncode($header);

        $payload = json_encode($payload);
        $payload = $this->base64UrlEncode($payload);

        $signature = hash_hmac(
            "sha256",
            $header . "." . $payload,
            $this->key, //this key will generate by 256bit encription key genarator.
            true
        );
        $signature = $this->base64UrlEncode($signature);

        return $header . "." . $payload . "." . $signature;
    }

    public function decode(String $token): array
    {
        if (preg_match(
            "/^(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/",
            $token,
            $matches
        ) !== 1) {
            throw new InvalidArgumentException("invalid token format");
        }

        $signature = hash_hmac(
            "sha256",
            $matches["header"] . "." . $matches["payload"],
            $this->key, //this key will generate by 256bit encription key genarator.
            true
        );
        $signatureFromToken = $this->base64UrlDecode($matches["signature"]);

        if (!hash_equals($signature, $signatureFromToken)) {
            throw new Exception("Signature doesnt match");
        }

        $payload = json_decode($this->base64UrlDecode($matches["payload"]), true);
        return $payload;
    }

    private function base64UrlEncode(string $text): string
    {
        return str_replace(
            ["+", "/", "="],
            ["-", "_", ""],
            base64_encode($text)
        );
    }

    private function base64UrlDecode(string $text): string
    {
        return base64_decode(
            str_replace(
                ["-", "_"],
                ["+", "/"],
                $text
            )
        );
    }
}
