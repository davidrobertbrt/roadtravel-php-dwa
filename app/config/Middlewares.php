<?php

return array(
    '*' => array('HitMiddleware','AuthMiddleware','RoleMiddleware','FormParse'),
    'register@process'=>array('RegisterFormParse'),
    'location@process'=>array('GeolocationFetch'),
    'location@create'=>array('GeolocationFetch'),
    'trip@create'=>array('TripFormParse'),
    'trip@process'=>array('TripFormParse')
);