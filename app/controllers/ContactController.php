<?php

class ContactController extends Controller
{
    public function index()
    {
        $this->render('ContactIndex');
    }

    public function process()
    {
        $emailSender = EmailSender::getInstance();
        $formData = $this->request->getData();

        $name    = $formData['name'] ?? '';
        $subject = $formData['subject'] ?? '';
        $message = $formData['message'] ?? '';

        if (empty($name) ||  empty($subject) || empty($message)) {
            $response = new Response('Please fill in all fields.',403);
            $response->send();
        }

        $body = <<<EOT
        <h2>AerialTravel</h2>
        <h2>Un nou mesaj a fost primit:</h2>
        <p><strong>Nume:</strong> $name</p>
        <p><strong>Subiect:</strong> $subject</p>
        <p><strong>Mesaj:</strong></p>
        <p>$message</p>
        EOT;

        $emailSender->send(Secrets::$EMAIL_WHERETO, "Mesaj nou:{$subject}", $body, Secrets::$EMAIL_USERNAME, $name);

        $this->render('ContactSuccess');
    }
}