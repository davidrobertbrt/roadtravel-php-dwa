<?php

class UserController extends Controller
{
    public function index()
    {
        $users = UserRepository::readAll();
        $results = array();

        foreach($users as $user)
        {
            $id = $user->getId();
            $name = $user->getFirstName() . ' ' . $user->getLastName();
            $email = $user->getEmailAddress();
            $address = $user->getAddress();
            $phoneNumber = $user->getPhoneNumber();
            $dateOfBirth = $user->getDateOfBirth();

            $role = RoleRepository::readByUserId($id)->getRole();
            
            $results[] = array(
                'id' => $id,
                'name' => $name,
                'address'=>$address,
                'email' => $email,
                'phoneNumber' => $phoneNumber,
                'dateOfBirth' => $dateOfBirth,
                'role' => $role
            ); 
        }

        $this->viewData['results'] = $results;
        $this->render('UserIndex',$this->viewData);
    }

    public function promote()
    {
        $formData = $this->request->getData();
        $idUser = $formData['id'];
        
        $role = RoleRepository::readByUserId($idUser);
        $role->setRole("admin");

        $checkUpdate = RoleRepository::update($role);

        if($checkUpdate === false)
        {
            $res = new Response('Failure at update',500);
            $res->send();
            exit();
        }
        
        $res = new Response("User promoted!",200);
        $res->send();
    }

}