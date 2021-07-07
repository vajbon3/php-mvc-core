<?php


namespace vajbon3\mvc;


class Response
{
    public function setStatusCode(int $code) {
        http_response_code($code);
    }

    public function redirect(string $url)
    {
        header("Location: ".$url);
    }
}