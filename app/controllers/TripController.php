<?php
class TripController extends Controller
{

    public function __construct($request)
    {
        parent::__construct($request);
        $this->viewData['busRepo'] = BusRepository::readAll();
        $this->viewData['locationRepo'] = LocationRepository::readAll();
        $this->viewData['tripRepo'] = TripRepository::readAll();
    }

    public function index()
    {
        $this->render('TripIndex',$this->viewData);
    }

    public function create()
    {
        $formData = $this->request->getData();
        $busId = $formData['busId'];
        $departureId = $formData['departureId'];
        $arrivalId = $formData['arrivalId'];
        $dateTimeStart = $formData['dateTimeStart'];
        $dateTimeEnd = $formData['dateTimeEnd'];

        $trip = new Trip(null,$busId,$departureId,$arrivalId,$dateTimeStart,$dateTimeEnd);            

        $checkCreate = TripRepository::create($trip);

        if($checkCreate === false)
        {
            $res = new Response('Failure at creation',403);
            $res->send();
            exit();
        }

        $res = new Response('Trip created!',200);
        $res->send();
    }

    public function delete()
    {
        $formData = $this->request->getData();
        $id = $formData['id'];

        $trip = $this->viewData['tripRepo'][$id];
        
        $checkDelete = TripRepository::delete($trip);

        if($checkDelete === false)
        {
            $res = new Response('Failure at delete',500);
            $res->send();
            exit();
        }

        $res = new Response('Trip deleted!',200);
        $res->send();

    }

    public function edit()
    {
        $formData = $this->request->getData();
        $id = $formData['id'];
        $trip = $this->viewData['tripRepo'][$id];

        if(isset($trip) && !empty($trip))
        {
            $this->viewData['crTrip'] = $trip;
            $this->render('TripEdit',$this->viewData);
            return;
        }

        $res = new Response("Error at reading trip",500);
        $res->send();
        exit();
    }

    public function process()
    {
        $formData = $this->request->getData();

        $id = $formData['id'];
        $busId = $formData['busId'];
        $departureId = $formData['departureId'];
        $arrivalId = $formData['arrivalId'];
        $dateTimeStart = $formData['dateTimeStart'];
        $dateTimeEnd = $formData['dateTimeEnd'];

        $trip = $this->viewData['tripRepo'][$id];
        $trip->setBusId($busId);
        $trip->setLocationStartId($departureId);
        $trip->setLocationEndId($arrivalId);
        $trip->setDateTimeStart($dateTimeStart);
        $trip->setDateTimeEnd($dateTimeEnd);

        $checkUpdate = TripRepository::update($trip);

        if($checkUpdate === false)
        {
            $res = new Response('Failure at update',500);
            $res->send();
            exit();
        }

        $res = new Response('Trip updated!',200);
        $res->send();
    }
}