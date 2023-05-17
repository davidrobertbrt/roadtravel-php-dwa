<?php

class WeatherApi
{
    // utility class
    private function __construct() {}

    public static function getWeather($geopos)
    {
        if(empty($geopos))
            return null;

        $url = "https://api.open-meteo.com/v1/forecast?latitude={$geopos['latitude']}&longitude={$geopos['longitude']}&daily=temperature_2m_max,weathercode&timezone=auto";
        $data = file_get_contents($url);

        $weatherData = json_decode($data,true);
        $weather = array();

        $weatherExtract = $weatherData['daily'];

        if(empty($weatherExtract))
            return null;

        for($weatherIndex = 0; $weatherIndex < 7; $weatherIndex++)
        {
            $weatherEntry = array();
            $weatherEntry['date'] = $weatherExtract['time'][$weatherIndex];
            $weatherEntry['temperature'] = $weatherExtract['temperature_2m_max'][$weatherIndex];
            $weatherEntry['weatherCode'] = $weatherExtract['weathercode'][$weatherIndex];

            $weatherEntry['date'] = DateTime::createFromFormat('Y-m-d',$weatherEntry['date']);
            $weatherEntry['weatherCode'] = self::getWeatherCode($weatherEntry['weatherCode']);


            $weather[] = array(
                'date' => $weatherEntry['date'],
                'temperature'=>$weatherEntry['temperature'],
                'code'=>$weatherEntry['weatherCode']
            );
        }
    }

    public static function getWeatherCode($code)
    {
        switch ($code) {
            case 0:
                $code = 'Cer senin';
                break;
            case 1:
            case 2:
            case 3:
                $code = 'În principal senin, parțial înnorat și acoperit';
                break;
            case 45:
            case 48:
                $code = 'Ceată și ceață înghețată';
                break;
            case 51:
            case 53:
            case 55:
                $code = 'Stropiță: Intensitate ușoară, moderată și densă';
                break;
            case 56:
            case 57:
                $code = 'Stropiță înghețată: Intensitate ușoară și densă';
                break;
            case 61:
            case 63:
            case 65:
                $code = 'Ploaie: Ușoară, moderată și intensă';
                break;
            case 66:
            case 67:
                $code = 'Ploaie înghețată: Intensitate ușoară și intensă';
                break;
            case 71:
            case 73:
            case 75:
                $code = 'Zăpadă: Ușoară, moderată și intensă';
                break;
            case 77:
                $code = 'Zăpadă granulată';
                break;
            case 80:
            case 81:
            case 82:
                $code = 'Averse de ploaie: Ușoare, moderate și violente';
                break;
            case 85:
            case 86:
                $code = 'Averse de zăpadă ușoare și intense';
                break;
            case 95:
                $code = 'Furtună: Ușoară sau moderată';
                break;
            case 96:
            case 99:
                $code = 'Furtună cu grindină ușoară și intensă';
                break;
            default:
                $code = 'Necunoscut';
                break;
            }

        return $code;
    }

}