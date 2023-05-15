<?php

class SanitizeMiddleware implements Middleware
{
    public function __invoke($req)
    {
        $formData = $req->getData();

        if(!isset($formData))
        {
            $req = new Request("Form is empty.",403);
            return $req;
        }

        foreach($formData as $formKey => $formValue)
        {
            $formValue = trim($formValue);

            if(empty($formValue))
            {
                $req = new Request("Form is empty.",403);
                return $req;          
            }

            $formValue = stripslashes($formValue);
            $formValue = filter_var($formValue,FILTER_SANITIZE_STRING);

            $formData[$formKey] = $formValue;
        }

        $req->setData($formData);

        return $req;  
    }
    
}