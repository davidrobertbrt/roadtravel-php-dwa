<?php

class RegisterFormMiddleware implements Middleware{
    public function __invoke($req)
    {
        //1. sanitaze input (SanitizeMiddleware.php)
        //2. validate data
        //3. controller manipulation

        $formData = $req->getData();
        $errors = array();

        if(ctype_alpha($formData['firstName']) === false)
            $errors[] = 'First name must contain letters and spaces only.';

        if(ctype_alpha($formData['lastName']) === false)
            $errors[] = 'Last name must contain letters and spaces only.';

        if(!filter_var($formData['emailAddress'],FILTER_VALIDATE_EMAIL))
            $errors[] = 'Email is not valid';

        $date = DateTime::createFromFormat('Y-m-d',$formData['dateOfBirth']);
        $isDateValid = ($date && $date->format('Y-m-d') === $formData['dateOfBirth']);

        if(!$isDateValid)
            $errors[] = 'The date is not in the correct format.';
        else
            $formData['dateOfBirth'] = $date;
        
        $formatPhoneNumber = preg_replace('/\D/','',$formData['phoneNumber']);
        $isPhoneValid = (strlen($formatPhoneNumber) === 10) && ctype_digit($formatPhoneNumber);

        if(!$isPhoneValid)
            $errors[] = "The phone number is not valid.";
        else
            $formData['phoneNumber'] = $formatPhoneNumber;

        if($formData['password'] !== $formData['confirmPassword'])
            $errors[] = "Password is not confirmed.";

        $hasPwdUppercase = false;
        $hasPwdNumber = false;
        $hasMinLength = false;

        if(strlen($formData['password']) >= 8)
            $hasMinLength = true;

        for($i = 0; $i<strlen($formData['password']); $i++)
        {
            if(ctype_upper($formData['password'][$i]))
                $hasPwdUppercase = true;
        
            if(ctype_digit($formData['password'][$i]))
                $hasPwdNumber = true;

            if($hasPwdUppercase === true && $hasPwdNumber === true)
                break;
        }

        var_dump($hasPwdNumber);
        var_dump($hasPwdUppercase);
        var_dump($hasMinLength);
        if(!$hasPwdUppercase || !$hasPwdNumber || !$hasMinLength)
            $errors[] = 'Password is not in correct format';
        else
            $formData['password'] = password_hash($formData['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        if(count($errors) > 0)
            $req = new Response(implode("<br>",$errors),403);
        else
            $req->setData($formData);
    
        return $req;
    }

}