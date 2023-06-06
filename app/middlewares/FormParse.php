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
        if($req->getMethod() === 'GET')
            return $req;

        $descriptor = $req->getDescriptor();
        $rulesTable = require_once '../app/config/FormRules.php';

        $rules = $rulesTable[$descriptor] ?? [];

        $values = $req->getData();

        if(empty($values))
            return $req;
    
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

            if($isOpt && empty($value)) {
                continue; // Skip processing optional fields with empty values
            }

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
                        break;
                    case 'phone':
                        $sanitizedValue = $this->sanitizePhoneNumber($value);
                        break;
                    case 'range':
                        $sanitizedValue = $this->sanitizeRange($value);
                        break;
                    case 'checkbox':
                        $sanitizedValue = $this->sanitizeCheckbox($value);
                        break;
                    case 'date':
                        $sanitizedValue = $this->sanitizeDate($value);
                        break;
                    case 'time':
                        $sanitizedValue = $this->sanitizeTime($value);
                        break;
                    default:
                        break;
                }

                $isValid = $this->validate($inputType,$sanitizedValue);


                if(!$isValid)
                {
                    $res = new Response('Error at validation',403);
                    return $res;
                }

                $values[$inputName] = $sanitizedValue;
            }
            else
            {
                if(!$isOpt){
                    $res = new Response('Required field is not filled',403);
                    return $res;
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
                return DateTime::createFromFormat('Y-m-d H:i:s', $value . ' 00:00:00') !== false;
            case 'integer':
                return filter_var($value, FILTER_VALIDATE_INT) !== false;
            case 'text':
                return !empty($value);
            case 'phone':
                return strlen($value) <= 12;
            case 'range':
                return gettype($value) === 'integer';
            case 'checkbox':
                return empty($value);
                break;
            case 'date':
                return DateTime::createFromFormat('Y-m-d', $value) !== false;
                break;
            case 'time':
                return DateTime::createFromFormat('H:i', $value) !== false;
                break;
            default:
                return false;
        }
    }

    private function sanitizeText($text)
    {
        $text = trim($text); 
        $text = strip_tags($text); 

        return $text;
    }

    private function sanitizePhoneNumber($phoneNumber)
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber); 

        return $phoneNumber;
    }

    private function sanitizeInteger($integer)
    {
        $integer = preg_replace('/[^0-9]/', '', $integer);

        return intval($integer);
    }

    private function sanitizeDatetime($datetime)
    {
        $dateTimeObj = DateTime::createFromFormat('Y-m-d H:i:s', $datetime . ' 00:00:00'); 

        $formattedDatetime = $dateTimeObj->format('Y-m-d'); 
        return $formattedDatetime;
    }

    private function sanitizePassword($password)
    {
        $password = trim($password);

        return $password;
    }

    private function sanitizeRange($range)
    {
        $sanitizedValue = filter_var($range, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        return floatval($sanitizedValue);
    }

    private function sanitizeCheckbox($input)
    {
        return isset($input);
    }

    private function sanitizeDate($date)
    {
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);

        return $dateObj->format('Y-m-d');
    }

    private function sanitizeTime($time)
    {
        $timeObj = DateTime::createFromFormat('H:i', $time);

        return $timeObj->format('H:i');
    }

}