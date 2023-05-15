<?php

class GeolocationApi{

    // utility class
    private function __construct() {}

    public static function getGeopos($locationName)
    {
        $locationUrl = urlencode($locationName);
        $url = "https://geocoding-api.open-meteo.com/v1/search?name=".$locationUrl."&count=1&language=en&format=json";
        $data = file_get_contents($url);
        $geopos = json_decode($data, true);

        if(empty($geopos['results'][0]['latitude']) || empty($geopos['results'][0]['longitude']))
            return null;

        return array(
            'latitude' => $geopos['results'][0]['latitude'],
            'longitude' => $geopos['results'][0]['longitude']
        );
    }

}