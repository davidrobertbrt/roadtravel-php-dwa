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

    public static function calculateDistance($geopos1,$geopos2, $unit = 'km') {

        $latitude1 = $geopos1['latitude'];
        $longitude1 = $geopos1['longitude'];
        $latitude2 = $geopos2['latitude'];
        $longitude2 = $geopos2['longitude'];

        $earthRadius = ($unit === 'km') ? 6371 : 3959; // Radius of the Earth in kilometers or miles
    
        $latDiff = deg2rad($latitude2 - $latitude1);
        $lngDiff = deg2rad($longitude2 - $longitude1);
    
        $a = sin($latDiff / 2) * sin($latDiff / 2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($lngDiff / 2) * sin($lngDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        $distance = $earthRadius * $c;
    
        return $distance;
    }
    
    
}