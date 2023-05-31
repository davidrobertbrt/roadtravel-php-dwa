<?php

class LocationController extends Controller
{
    private $locationRepo;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->locationRepo = new LocationRepository();
    }


    public function index()
    {
        $viewData['locations'] = $this->locationRepo->readAll();
        $this->render('LocationIndex',$viewData);
    }

    public function create()
    {
        $formData = $this->request->getData();

        $name = $formData['name'];
        $latitude = empty($formData['latitude']) ? null : floatval($formData['latitude']);
        $longitude = empty($formData['longitude']) ? null : floatval($formData['longitude']);

        if($latitude === null || $longitude === null)
        {
            $geopos = GeolocationApi::getGeopos($name);
            $latitude = $geopos['latitude'];
            $longitude = $geopos['longitude'];
        }

        $locationInsert = Location::loadByParams(null,$name,$longitude,$latitude);
        $idInsert = $this->locationRepo->create($locationInsert);

        if($idInsert === null)
        {
            $request = new Response('The location already exists!',403);
            $request->send();
            return;
        }

        $request = new Response('Location inserted.',200);
        $request->send();
    }

    public function delete()
    {
        $formData = $this->request->getData();
        $id = $formData['id'];

        $location = $this->locationRepo->readById($id);

        if($location === null)
        {
            $response = new Response('Location does not exist',403);
            $response->send();
            return;
        }

        $checkDeletion = $this->locationRepo->delete($location);

        if($checkDeletion === false)
        {
            $response = new Response('There are other tables linked to the locations table.',403);
            $response->send();
            return;
        }

        $response = new Response('Location deleted',200);
        $response->send();

    }

    public function edit()
    {
        $formData = $this->request->getData();
        $id = $formData['id'];

        $location = $this->locationRepo->readById($id);

        if($location === null)
        {
            $response = new Response('Location does not exist',403);
            $response->send();
            return;
        }

        $viewData['crLocation'] = $location;

        $this->render('LocationEdit',$viewData);
    }

    public function process()
    {
        $formData = $this->request->getData();
        $id = $formData['id'];
        $name = $formData['name'];
        $latitude = empty($formData['latitude']) ? null : floatval($formData['latitude']);
        $longitude = empty($formData['longitude']) ? null : floatval($formData['longitude']);

        if($latitude === null || $longitude === null)
        {
            $geopos = GeolocationApi::getGeopos($name);

            if($geopos === null)
            {
                $response = new Response("API couldn't find city!",403);
                $response -> send();
                return;
            }
            
            $latitude = $geopos['latitude'];
            $longitude = $geopos['longitude'];
        }
        

        $location = Location::loadByParams($id,$name,$longitude,$latitude);

        $checkUpdate = $this->locationRepo->update($location);

        if($checkUpdate === false)
        {
            $response = new Response('Update failed',403);
            $response->send();
            return;
        }

        $response = new Response('Update succesfully made.',200);
        $response->send();

    }

}