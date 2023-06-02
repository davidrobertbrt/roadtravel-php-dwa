<?php

final class AuthMiddleware implements Middleware
{
    public function __invoke($req){
        $descriptor = $req->getDescriptor();
        $pathCase = $this->checkPathCase($descriptor);

        // treat the case when the user is logged in. 
        // if it is, he should not enter the login, register pages...
        if($pathCase === true)
        {
            if(isset($_SESSION['user']))
            {
                $urlPath = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
                //User is logged in, we should just start over.
                header("Location: " . $urlPath);
                exit();
            }
    
            // User is not authentificated, go on.
            return $req;
        }

        if(!isset($_SESSION['user']))
        {
            //User is not logged in, returning response
            $response = new Response('Unauthorized, please log in.',401);
            return $response;
        }

        // User is authentificated, return the request to pass it to the controller.
        return $req;

    }

    private function checkPathCase($descriptor)
    {
        $patterns = array('login','register');
        foreach($patterns as $pattern)
        {
            if(substr($descriptor,0,strlen($pattern)) === $pattern)
                return true;
        }

        return false;
    }

}