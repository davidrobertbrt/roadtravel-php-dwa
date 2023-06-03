<?php

class LocationController extends Controller
{
    private $locationRepo;

    public function __construct($request)
    {
        parent::__construct($request);
    }


    public function index()
    {
        $viewData['locations'] = LocationRepository::readAll();
        $this->render('LocationIndex',$viewData);
    }

    public function create()
    {
        $formData = $this->request->getData();

        $name = $formData['name'];
        $latitude = $formData['latitude'];
        $longitude = $formData['longitude'];

        $locationInsert = Location::loadByParams(null,$name,$longitude,$latitude);
        $checkInsert = LocationRepository::create($locationInsert);

        if($checkInsert === false)
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

        $location = LocationRepository::readById($id);

        if($location === null)
        {
            $response = new Response('Location does not exist',403);
            $response->send();
            return;
        }

        $checkDeletion = LocationRepository::delete($location);

        if($checkDeletion === false)
        {
            $response = new Response('There are other tables linked to the locations table.',500);
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

        $location = LocationRepository::readById($id);

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
        $latitude = $formData['latitude'];
        $longitude = $formData['longitude'];

        $location = Location::loadByParams($id,$name,$longitude,$latitude);

        $checkUpdate = LocationRepository::update($location);

        if($checkUpdate === false)
        {
            $response = new Response('Update failed',500);
            $response->send();
            return;
        }

        $response = new Response('Update succesfully made.',200);
        $response->send();
    }
}