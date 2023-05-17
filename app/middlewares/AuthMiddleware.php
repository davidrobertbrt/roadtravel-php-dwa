<?php

class AuthMiddleware implements Middleware
{
    public function __invoke($req){

        if(!isset($_SESSION['user']))
        {
            //User is not logged in, returning response
            $response = new Response('Unauthorized, please log in.',401);
            return $response;
        }

        // User is authentificated, return the request to pass it to the controller.
        return $req;
    }
}