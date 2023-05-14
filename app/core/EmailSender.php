<?php

require_once '../app/vendor/phpmailer/class.phpmailer.php';
//Static class which contains the $EMAIL_HOST,$EMAIL_USERNAME,$EMAIL_PASSWORD as static fields
require_once '../app/config/Secrets.php';

final class EmailSender
{
    private static $instance = null;
    private $mailer = null;

    private function __construct()
    {
        $this->mailer = new PhpMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->SMTPAuth = true;
        $this->mailer->SMTPSecure = "ssl";                 
        $this->mailer->Host       = Secrets::$EMAIL_HOST;      
        $this->mailer->Port       = 465;                   
        $this->mailer->Username   = Secrets::$EMAIL_USERNAME;
        $this->mailer->Password   = Secrets::$EMAIL_PASSWORD;
        $this->mailer->Helo = "idigit.ro";
    }

    public static function getInstance()
    {
        if(self::$instance === null)
            self::$instance = new EmailSender();

        return self::$instance;
    }

    public function send($to, $subject, $body, $from = null, $name = null, $isHtml = true)
    {
        try {
            // Set the sender and recipient addresses
            $this->mailer->setFrom($from ?? $this->mailer->From, $name ?? $this->mailer->FromName);
            $this->mailer->addAddress($to);
            
            // Set the email subject and body
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;
            
            // Set the email format
            if ($isHtml) 
            {
                $this->mailer->isHTML(true);
            } 
            else 
            {
                $this->mailer->isHTML(false);
                $this->mailer->AltBody = strip_tags($body); // Set the plain text version of the email body
            }
            
            // Send the email
            return $this->mailer->send();
        } 
        catch (Exception $e) 
        {
            throw $e;
        }
    }
}