<?php

class LoginMiddleware implements Middleware
{
    public function __invoke($data){
        if(isset($_SESSION['user']))
        {
            $urlPath = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
            //User is logged in, we should just start over.
            header("Location: " . $urlPath);
            exit();
        }

        // User is not authentificated, go on.
        return $data;
    }
}