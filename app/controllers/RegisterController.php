<?php

class RegisterController extends Controller
{
    private $userRepo;
    private $credentialRepo;

    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function index()
    {
        $this->render('RegisterIndex');
    }

    public function process()
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
        $idInsertUser = UserRepository::create($user);
        $user->setId($idInsertUser);
        
        //encrypt password in middleware

        $credential = Credential::loadByParams(null,$idInsertUser,$formData['password']);
        $idInsertCredential = CredentialRepository::create($credential);
        $credential->setId($idInsertCredential);

        $response = new Response("User created.",200);
        $response->send();
        exit();
        

    }
}