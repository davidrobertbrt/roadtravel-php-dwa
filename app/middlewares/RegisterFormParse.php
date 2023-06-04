<?php

class RegisterFormParse implements Middleware{

    public function __invoke($req)
    {
        $data = $req->getData();

        if(strcmp($data['password'],$data['confirmPassword']) !== 0)
        {
            $res = new Response("Password is not confirmed",403);
            return $res;
        }

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        $req->setData($data);

        return $req;
    }

}