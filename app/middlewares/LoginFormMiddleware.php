<?php
class LoginFormMiddleware implements Middleware{
    public function __invoke($req)
    {
        $formData = $req->getData();

        $errors = array();

        if(!filter_var($formData['email'],FILTER_VALIDATE_EMAIL))
            array_push($errors,'Email is not valid');
    
        if(count($errors) > 0)
            $req = new Response(implode("<br>",$errors),403);
        
        return $req;
    }
}