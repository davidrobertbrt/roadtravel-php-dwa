<?php

final class Response{
    private $statusCode;
    private $content;
    private $redirect;

    public function __construct($content, $statusCode)
    {
        $this->statusCode = $statusCode;
        $this->content = $content;
    }

    public function redirectTo($url)
    {
        $this->redirect = $url;
    }

    public function send(){

        if(isset($this->redirect))
        {
            header("Location: {$this->redirect}");
            exit();
        }

        //HTTP OK.
        $this->statusCode ??= 200;
        http_response_code($this->statusCode);

        if(isset($this->content))
            echo $this->content;

        exit();
    }
}