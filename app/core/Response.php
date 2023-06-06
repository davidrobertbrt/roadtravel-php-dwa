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
        if(!isset($this->statusCode))
            $this->statusCode = 200;
        
        http_response_code($this->statusCode);

        $previousPage = $_SERVER['HTTP_REFERER'];


        if(isset($this->content))
        {
            if(isset($previousPage))
                $this->content = $this->content . '<a href="'.$previousPage.'">Go Back</a>';
            
            echo $this->content;
        }

    }
}