<?php

final class Response{
    private $statusCode;
    private $content;

    public function __construct($content, $statusCode)
    {
        $this->statusCode = $statusCode;
        $this->content = $content;
    }

    public function send(){
        //HTTP OK.
        $this->statusCode ??= 200;
        http_response_code($this->statusCode);

        if(isset($this->content))
            echo $this->content;
    }
}