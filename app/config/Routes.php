<?php

/**
 * File used to store all routes for the application. 
 * It separates each request based on the request data sent (either GET or POST)
 * 
 * @return array Associative array based on the method of request sent and all the routes for each one of them.
 */

return array(
    'GET' => array(
        // This is the default handler for / URL
        '@' => array('controller' => 'HomeController','action'=>'index'),
        'example@index' => array('controller' => 'ExampleController','action'=>'index'),
        'login@index' => array('controller' => 'LoginController','action'=>'index'),
        'logout@process' => array('controller'=>'LogoutController','action'=>'process'),
        'register@index' => array('controller' => 'RegisterController','action'=>'index'),
        'home@index' => array('controller'=>'HomeController','action'=>'index'),
        'contact@index' => array('controller'=>'ContactController','action'=>'index')
    ),
    'POST' => array(
        'login@process' => array('controller'=>'LoginController','action'=>'process'),
        'register@process' => array('controller'=>'RegisterController','action'=>'process'),
        'contact@process' => array('controller'=>'ContactController','action'=>'process')
    ),
);