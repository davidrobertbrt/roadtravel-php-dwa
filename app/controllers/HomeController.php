<?php

class HomeController extends Controller
{
    public function index()
    {
        if(isset(StatisticsRepository::$stats))
            $this->viewData['stats'] = StatisticsRepository::$stats;

        $this->render('HomeIndex',$this->viewData);
    }

    public function fetchWeather()
    {
        $formData = $this->request->getData();
        $cityName = $formData['city'];

        $geopos = GeolocationApi::getGeopos($cityName);
        $weatherData = WeatherApi::getWeather($geopos);

        $this->viewData['weather'] = $weatherData; 
        
        if(isset(StatisticsRepository::$stats))
            $this->viewData['stats'] = StatisticsRepository::$stats;

        $this->render('HomeIndex',$this->viewData);
    }

    public function getStatistics()
    {
        $checkAccess = StatisticsRepository::generate();
        if($checkAccess === false)
        {
            $response = new Response("Error at fetching stats",500);
            $response->send();
            return;
        }

        if(isset(StatisticsRepository::$stats))
            $this->viewData['stats'] = StatisticsRepository::$stats;

        $this->render('HomeIndex',$this->viewData);
    }

    public function printStats()
    {
        if(isset(StatisticsRepository::$stats))
            $this->viewData['stats'] = StatisticsRepository::$stats;
        else
        {
            $checkAccess = StatisticsRepository::generate();
            if($checkAccess === true)
                $this->viewData['stats'] = StatisticsRepository::$stats;
            else
            {
                $response = new Response('Error at fetching',500);
                $response->send();
                return;
            }
        }

        $pdf = new HitReport();
        $pdf->generateReport($this->viewData['stats']);

    }
}