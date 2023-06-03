<?php

final class GeolocationFetch implements Middleware{

    public function __invoke($req)
    {
        $formData = $req->getData();
        $latitude = empty($formData['latitude']) ? null : $formData['latitude'];
        $longitude = empty($formData['longitude']) ? null : $formData['longitude'];

        if($latitude === null || $longitude === null)
        {
            $geopos = GeolocationApi::getGeopos($name);
           
            if($geopos === null)
            {
                $response = new Response("API couldn't find city!",403);
                $response -> send();
                die();
            }

            $latitude = $geopos['latitude'];
            $longitude = $geopos['longitude'];
        }

        $formData['latitude'] = $latitude;
        $formData['longitude'] = $longitude;

        $req->setData($formData);

        return $req;
    }

}