<?php

class HomeController extends Controller
{
    public function index()
    {
        $this->render('HomeIndex');
    }

    public function fetchWeather()
    {
        $formData = $this->request->getData();
        $cityName = $formData['city'];

        $geopos = GeolocationApi::getGeopos($cityName);
        $weatherData = WeatherApi::getWeather($geopos);

        $this->viewData['weather'] = $weatherData;
        $this->render('HomeIndex',$this->viewData);
    }
}