<?php

class RegisterController extends Controller
{
    public function index()
    {
        $this->render('RegisterIndex');
    }

    public function process()
    {
        var_dump($request->getData());
    }
}