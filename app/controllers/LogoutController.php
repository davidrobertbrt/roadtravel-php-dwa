<?php

class LogoutController extends Controller{

    public function process()
    {
        if(isset($_SESSION['user']))
        {
            // unset all session variables
            session_unset();
            // destroy the session
            session_destroy(); 
            $urlPath = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login/index';
            header("Location: " . $urlPath);
            exit();
        }
    }

}