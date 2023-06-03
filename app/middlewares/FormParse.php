<?php


/**
 * 1. Sanitaze input
 * 2. Validate data
 * 3. Escape output
 */

final class FormParse implements Middleware
{
    public function __invoke($req)
    {
        $descriptor = $req->getDescriptor();
        $rulesTable = require_once '../app/config/Forms.php';

        $rules = $rulesTable[$descriptor] ?? [];

        $values = $req->getData();

        if(empty($rules))
        {
            $res = new Response('Error at validation',500);
            $res->send();
            die();
        }

        foreach($rules as $inputName => $inputRule)
        {
            $value = $values[$inputName];
            $inputType = $inputRule['type'];
            $isOpt = $inputRule['opt'];


            if(!empty($value))
            {
                switch($inputType)
                {
                    case 'email':
                        $sanitizedValue = filter_var($value,FILTER_SANITIZE_EMAIL);
                        break;
                    case 'password':
                        $sanitizedValue = $this->sanitizePassword($value);
                        break;
                    case 'datetime':
                        $sanitizedValue = $this->sanitizeDatetime($value);
                        break;
                    case 'integer':
                        $sanitizedValue = $this->sanitizeInteger($value);
                        break;
                    case 'text':
                        $sanitizedValue = $this->sanitizeText($value);
                    case 'phone':
                        $sanitizedValue = $this->sanitizePhoneNumber($value);
                        break;
                    default:
                        break;
                }

                $isValid = $this->validate($inputType,$sanitizedValue);

                if(!$isValid)
                {
                    $res = new Response('Error at validation',403);
                    $res->send();
                    die();
                }

                $values[$inputName] = $sanitizedValue;
            }
            else
            {
                if(!$isOpt){
                    $res = new Response('Required field is not filled',403);
                    $res->send();
                    die();
                }
            }
        }

        $req->setData($values);
        return $req;
    }


    private function validate($inputType,$value)
    {
        // Perform validation logic based on the input type and value
        switch ($inputType) 
        {
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
            case 'password':
                return strlen($value) >= 8;
            case 'datetime':
                return DateTime::createFromFormat('Y-m-d H:i:s', $value) !== false;
            case 'integer':
                return filter_var($value, FILTER_VALIDATE_INT) !== false;
            case 'text':
                return !empty($value);
            case 'phone':
                return preg_match('/\d{12}/', $value);
            default:
                return false;
        }
    }

    private function sanitizeText(string $text): string
    {
        $text = trim($text); 
        $text = strip_tags($text); 

        return $text;
    }

    private function sanitizePhoneNumber(string $phoneNumber): string
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber); 

        return $phoneNumber;
    }

    private function sanitizeInteger(string $integer): int
    {
        $integer = preg_replace('/[^0-9]/', '', $integer);

        return intval($integer);
    }

    private function sanitizeDatetime(string $datetime): string
    {
        $dateTimeObj = DateTime::createFromFormat('Y-m-d H:i:s', $datetime); 

        $formattedDatetime = $dateTimeObj->format('Y-m-d H:i:s'); 
        return $formattedDatetime;
    }

    private function sanitizePassword(string $password): string
    {
        $password = trim($password);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        return $hashedPassword;
    }

}