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

        $previousPage = $this->constructGoBack();

        if(isset($this->content))
        {
            if(isset($previousPage))
                $this->content = $this->content . '<a href="'.$previousPage.'">Go Back</a>';
            
            echo $this->content;
        }

    }

    private function constructGoBack()
    {
        // this is going to be built in the middleware i think
        if(isset($_SESSION['prev_url']))
            return $_SESSION['prev_url'];
    }

}