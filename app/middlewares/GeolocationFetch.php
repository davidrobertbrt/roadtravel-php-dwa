<?php

final class GeolocationFetch implements Middleware{

    public function __invoke($req)
    {
        $formData = $req->getData();
        $latitude = empty($formData['latitude']) ? null : $formData['latitude'];
        $longitude = empty($formData['longitude']) ? null : $formData['longitude'];
        $name = $formData['name'];

        if($latitude === null && $longitude === null)
        {
            $geopos = GeolocationApi::getGeopos($name);
           
            if($geopos === null)
            {
                $response = new Response("API couldn't find city!",403);
                return $response;
            }

            $latitude = $geopos['latitude'];
            $longitude = $geopos['longitude'];
        }
        else
        {
            if($latitude === null || $longitude === null)
            {
                $response = new Response("One of the latitude and longtitude fields are not filled!",403);
                return $response;
            }
        }

        $formData['latitude'] = $latitude;
        $formData['longitude'] = $longitude;

        $req->setData($formData);

        return $req;
    }

}