<?php

final class SessionController extends Controller{

    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function formLogin()
    {
        $this->render('LoginIndex');
    }

    public function formRegister()
    {
        $this->render('RegisterIndex');
    }

    public function login()
    {
        $data = $this->request->getData();
        $crUser = UserRepository::readByEmail($data['email']);

        if(!isset($crUser))
        {
            $response = new Response("User is not found",403);
            $response->send();
            exit();
        }

        $fetchedUserId = $crUser->getId();
        $crCredentials = CredentialRepository::readByUserId($fetchedUserId);
        $fetchedPassword=  $crCredentials->getPassword();

        if(!password_verify($data['password'],$fetchedPassword))
        {
            $response = new Response("Password is not valid",403);
            $response->send();
            exit();
        }

        $urlPath = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
        $_SESSION['user'] = $fetchedUserId;
        header("Location: " . $urlPath);
        exit();
    }

    public function logout()
    {
        if(isset($_SESSION['user']))
        {
            // unset all session variables
            session_unset();
            // destroy the session
            session_destroy(); 
            $urlPath = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login/index';
            header("Location: " . $urlPath);
            exit();
        }
    }

    public function register()
    {
        $formData = $this->request->getData();

        $user = UserRepository::readByEmail($formData['emailAddress']);

        if(isset($user))
        {
            $req = new Request("User already exists!",403);
            $req->send();
        }

        // date of birth conversion in middleware

        $user = User::constructNoId($formData['emailAddress'],$formData['firstName'],$formData['lastName'],$formData['dateOfBirth'],$formData['lastName'],$formData['phoneNumber'],$formData['address']);
        $checkInsertion = UserRepository::create($user);

        if($checkInsertion === false)
        {
            $response = new Response("Error at insertion",500);
            $response->send();
        }
        
        //encrypt password in middleware

        $credential = Credential::loadByParams(null,$idInsertUser,$formData['password']);
        $checkInsertion = CredentialRepository::create($credential);

        if($checkInsertion === false)
        {
            UserRepository::delete($user);
            $response = new Response("Error at insertion",500);
            $response->send();
        }

        $response = new Response("User created.",200);
        $response->send();
        exit();
    }
}