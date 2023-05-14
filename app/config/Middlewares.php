<?php

return array(
    '@' => array(
        //'AuthMiddleware','ExampleMiddleware'
    ),
    'login@index'=>array(
        'LoginMiddleware'
    ),
    'login@process'=>array(
        'LoginMiddleware','LoginFormMiddleware'
    ),
    'logout@process'=>array(
        'AuthMiddleware'
    ),
    'register@index'=>array(
        'LoginMiddleware'
    ),
    'register@process'=>array(
        'LoginMiddleware',
    ),
    'home@index' => array(
        'AuthMiddleware'
    ),
);