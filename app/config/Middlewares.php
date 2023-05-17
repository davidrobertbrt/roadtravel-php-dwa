<?php

return array(
    '@' => array(
        //'AuthMiddleware','ExampleMiddleware'
    ),
    'login@index'=>array(
        'LoginMiddleware'
    ),
    'login@process'=>array(
        'LoginMiddleware','SanitizeMiddleware','LoginFormMiddleware'
    ),
    'logout@process'=>array(
        'AuthMiddleware'
    ),
    'register@index'=>array(
        'LoginMiddleware'
    ),
    'register@process'=>array(
        'LoginMiddleware','SanitizeMiddleware','RegisterFormMiddleware'
    ),
    'home@index' => array(
        'AuthMiddleware'
    ),
    'contact@index' => array(
        'AuthMiddleware'
    ),
    'contact@process'=>array(
        'AuthMiddleware','SanitizeMiddleware'
    )
);