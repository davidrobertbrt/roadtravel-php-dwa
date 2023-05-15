<?php

class LoginController extends Controller
{
    private $userRepo;
    private $credentialRepo;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->userRepo = new UserRepository();
        $this->credentialRepo = new CredentialRepository();
    }

    public function index()
    {
        $this->render('LoginIndex');
    }

    public function process()
    {
        $data = $this->request->getData();
        $user = $this->userRepo->readByEmail($data['email']);


        if(!isset($user))
        {
            $response = new Response("User is not found",403);
            $response->send();
            exit();
        }

        $credentials = $this->credentialRepo->readByUserId($user->getId());

        if(!password_verify($data['password'],$credentials->getPassword()))
        {
            $response = new Response("Password is incorrect",403);
            $response->send();
            exit();
        }

        $urlPath = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';

        $_SESSION['user'] = $user->getId();
        header("Location: " . $urlPath);
        exit();
    }
}