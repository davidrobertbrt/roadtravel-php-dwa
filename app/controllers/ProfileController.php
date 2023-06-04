<?php

class ProfileController extends Controller
{

    public function index()
    {
        $this->render("ProfileIndex");
    }

    public function submit()
    {
        $formData = $this->request->getData();

        $crIdUser = intval($_SESSION['user']);
        $crCredential = CredentialRepository::readByUserId($crIdUser);

        // check the password
        $password = $formData['password'];

        if(!password_verify($password,$crCredential->getPassword()))
        {
            $response = new Response("Password is incorrect",403);
            $response->send();
            return;
        }

        $user = UserRepository::readById($crIdUser);

        $user->setFirstName($formData['firstName']);
        $user->setLastName($formData['lastName']);
        $user->setDateOfBirth($formData['dateOfBirth']);
        $user->setPhoneNumber($formData['phoneNumber']);
        $user->setAddress($formData['address']);

        $checkUpdate = UserRepository::update($user);

        if($checkUpdate === false)
        {
            $response = new Response("Error DB",500);
            $response->send();
            return;
        }

        $response = new Response("Profile updated",200);
        $response->send();
    }

}