<?php

/**
 * Base controller class
 * Has methods to call the appropriate model and view.
 * View also gets the $_POST and $_GET data through the array $data, if there is any.
 */

class Controller{

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function render($view, $data = array())
    {
        require_once '../app/views/' . $view . '.php';
    }
}