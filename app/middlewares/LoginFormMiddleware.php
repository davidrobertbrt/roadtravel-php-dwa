<?php
class LoginFormMiddleware implements Middleware{
    public function __invoke($data)
    {
        $formData = $data->getData();

        $errors = array();

        if(!filter_var($formData['email'],FILTER_VALIDATE_EMAIL))
            array_push($errors,'Email is not valid');
    
        if(count($errors) > 0)
            $data = new Response(implode("|",$errors),403);
        
        return $data;
    }
}