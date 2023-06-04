<?php

final class SessionController extends Controller{

    public function __construct($request)
    {
        parent::__construct($request);
    }

    private function generateResetCode($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = rand(0, strlen($characters) - 1);
            $code .= $characters[$randomIndex];
        }
    
        return $code;
    }

    private function generateResetURL($resetCode) {
        $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/reset/confirm/' . $resetCode;
        return $baseUrl;
    }
    

    public function formLogin()
    {
        $this->render('LoginIndex');
    }

    public function formRegister()
    {
        $this->render('RegisterIndex');
    }

    public function formReset()
    {
        $this->render('ResetIndex');
    }

    public function formResetSend()
    {
        $emailSender = EmailSender::getInstance();
        $formData = $this->request->getData();
        $email = $formData['email'];

        $user = UserRepository::readByEmail($email);

        if(!isset($user))
        {
            $response = new Response("User does not exist",403);
            $response->send();
            die();
        }
    
        $credentials = CredentialRepository::readByUserId($user->getId());
        $resetCode = $this->generateResetCode();
        
        $_SESSION['reset'] = array(
            'credentialId'=>$credentials->getId(),
            'code'=>$resetCode
        );

        $link = $this->generateResetURL($resetCode);
        
        $body = <<<EOT
        <h2>RoadTravel</h2>
        <h2>Link-ul de resetare al parolei a fost generat!</h2>
        <a href="$link">Resetează parola</a>
        EOT;


        $emailSender->send($user->getEmailAddress(), "Resetarea parolei: RoadTravel", $body, Secrets::$EMAIL_USERNAME, "RoadTravel");

        $response = new Response("Sent!",200);
        $response->send();
    }

    public function formResetConfirm()
    {
        $formData = $this->request->getData();
        $resetCode = $formData[0];

        if(!isset($_SESSION['reset']))
        {
            $response = new Response("Error",500);
            $response->send();
            exit();
        }

        if($resetCode !== $_SESSION['reset']['code'])
        {
            $response = new Response("Cod invalid!",403);
            $response->send();
            exit();
        }
        
        $this->render('ResetConfirm');

    }

    public function resetProcess()
    {
        $formData = $this->request->getData();

        if(!isset($_SESSION['reset']))
        {
            $response = new Response("Error",500);
            $response->send();
            exit();
        }

        if($formData['password'] !== $formData['confirmPassword'])
        {
            $response = new Response("Error",403);
            $response->send();
            exit();
        }

        var_dump($_SESSION['reset']);

        $password = password_hash($formData['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        $credential = CredentialRepository::readById(intval($_SESSION['reset']['credentialId']));
        $credential->setPassword($password);

        $checkUpdate = CredentialRepository::update($credential);

        if($checkUpdate === false)
        {
            $response = new Response("Error",500);
            $response->send();
            exit();
        }


        $response = new Response("Password updated.",200);
        $response->send();

        unset($_SESSION['reset']);
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
        $fetchedEmail = $crUser->getEmailAddress();
        $fetchedRole = RoleRepository::readByUserId($fetchedUserId)->getRole();
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
        $_SESSION['email'] = $fetchedEmail;
        $_SESSION['role'] = $fetchedRole;
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
            $req = new Response("User already exists!",403);
            $req->send();
        }


        $user = User::constructNoId($formData['emailAddress'],$formData['firstName'],$formData['lastName'],$formData['dateOfBirth'],$formData['phoneNumber'],$formData['address']);
        $checkInsertion = UserRepository::create($user);

        if($checkInsertion === false)
        {
            $response = new Response("Error at insertion",500);
            $response->send();
        }
        
        $idInsertUser = $user->getId();

        $credential = Credential::loadByParams(null,$idInsertUser,$formData['password']);
        $checkInsertion = CredentialRepository::create($credential);

        if($checkInsertion === false)
        {
            UserRepository::delete($user);
            $response = new Response("Error at insertion",500);
            $response->send();
        }

        $role = Role::constructNoId($idInsertUser,'default');

        $checkInsertion = RoleRepository::create($role);

        if($checkInsertion === false)
        {
            CredentialRepository::delete($credential);
            UserRepository::delete($user);
            $response = new Response("Error at insertion",500);
            $response->send();
        }

        $response = new Response("User created.",200);
        $response->send();
        exit();
    }
}