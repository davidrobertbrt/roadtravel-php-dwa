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
        '@' => array('controller' => 'ExampleController','action'=>'index'),
    ),
    'POST' => array(
        
    ),
);