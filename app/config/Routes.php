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
        'login@index' => array('controller' => 'SessionController','action'=>'formLogin'),
        'logout@process' => array('controller'=>'SessionController','action'=>'logout'),
        'register@index' => array('controller' => 'SessionController','action'=>'formRegister'),
        'home@index' => array('controller'=>'HomeController','action'=>'index'),
        'contact@index' => array('controller'=>'ContactController','action'=>'index'),
        'location@index' => array('controller'=>'LocationController','action'=>'index'),
        'bus@index' => array('controller'=>'BusController','action'=>'index'),
        'trip@index' => array('controller'=>'TripController','action'=>'index'),
        'discount@index'=>array('controller'=>'DiscountController','action'=>'index'),
        'booking@index'=>array('controller'=>'BookingController','action'=>'index'),
        'reset@index'=>array('controller'=>'SessionController','action'=>'formReset'),
        'reset@confirm'=>array('controller'=>'SessionController','action'=>'formResetConfirm'),
        'tickets@index'=>array('controller'=>'TicketController','action'=>'index'),
        'user@index' => array('controller'=>'UserController','action'=>'index'),
        'profile@index'=>array('controller'=>'ProfileController','action'=>'index')  
    ),
    'POST' => array(
        'login@process' => array('controller'=>'SessionController','action'=>'login'),
        'register@process' => array('controller'=>'SessionController','action'=>'register'),
        'contact@process' => array('controller'=>'ContactController','action'=>'process'),
        'location@create' => array('controller'=>'LocationController','action'=>'create'),
        'location@delete' => array('controller'=>'LocationController','action'=>'delete'),
        'location@edit' => array('controller'=>'LocationController','action'=>'edit'),
        'location@process' => array('controller'=>'LocationController','action'=>'process'),
        'bus@create' => array('controller'=>'BusController','action'=>'create'),
        'bus@edit' => array('controller'=>'BusController','action'=>'edit'),
        'bus@process' => array('controller'=>'BusController','action'=>'process'),
        'bus@delete' => array('controller'=>'BusController','action'=>'delete'),
        'trip@create' => array('controller'=>'TripController','action'=>'create'),
        'trip@edit' => array('controller'=>'TripController','action'=>'edit'),
        'trip@process'=>array('controller'=>'TripController','action'=>'process'),
        'trip@delete'=>array('controller'=>'TripController','action'=>'delete'),
        'discount@create'=>array('controller'=>'DiscountController','action'=>'create'),
        'discount@edit'=>array('controller'=>'DiscountController','action'=>'edit'),
        'discount@process'=>array('controller'=>'DiscountController','action'=>'process'),
        'discount@delete'=>array('controller'=>'DiscountController','action'=>'delete'),
        'booking@fetchAvailableTrips'=>array('controller'=>'BookingController','action'=>'fetchAvailableTrips'),
        'booking@process'=>array('controller'=>'BookingController','action'=>'process'),
        'reset@send'=>array('controller'=>'SessionController','action'=>'formResetSend'),
        'reset@process'=>array('controller'=>'SessionController','action'=>'resetProcess'),
        'tickets@generate'=>array('controller'=>'TicketController','action'=>'generate'),
        'user@promote'=>array('controller'=>'UserController','action'=>'promote'),
        'profile@submit'=>array('controller'=>'ProfileController','action'=>'submit'),
        'home@fetchWeather'=>array('controller'=>'HomeController','action'=>'fetchWeather')
    ),
);