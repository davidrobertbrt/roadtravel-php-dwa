<?php
class TripController extends Controller
{

    public function __construct($request)
    {
        parent::__construct($request);
        $locationRepo = new LocationRepository();
        $this->viewData['busRepo'] = BusRepository::readAll();
        $this->viewData['locationRepo'] = $locationRepo->readAll();
        $this->viewData['tripRepo'] = TripRepository::readAll();
    }

    public function index()
    {
        $this->render('TripIndex',$this->viewData);
    }

    public function create()
    {
        $formData = $this->request->getData();
        $busId = intval($formData['busId']);
        $departureId = intval($formData['departureId']);
        $arrivalId = intval($formData['arrivalId']);
        $dateTimeStart = DateTime::createFromFormat('Y-m-d\TH:i',$formData['dateTimeStart']);
        $dateTimeEnd = DateTime::createFromFormat('Y-m-d\TH:i',$formData['dateTimeEnd']);

        if($departureId === $arrivalId)
            return;

        if($dateTimeStart === $dateTimeEnd)
            return;

        if($dateTimeStart > $dateTimeEnd)
            return;

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
        $id = intval($formData['id']);

        $trip = $this->viewData['tripRepo'][$id];
        
        $checkDelete = TripRepository::delete($trip);

        if($checkDelete === false)
        {
            $res = new Response('Failure at delete',403);
            $res->send();
            exit();
        }

        $res = new Response('Trip deleted!',200);
        $res->send();

    }

    public function edit()
    {
        $formData = $this->request->getData();
        $id = intval($formData['id']);
        $trip = $this->viewData['tripRepo'][$id];

        $this->viewData['crTrip'] = $trip;
        $this->render('TripEdit',$this->viewData);
    }

    public function process()
    {
        $formData = $this->request->getData();
        var_dump($formData);
        $id = intval($formData['id']);
        $busId = intval($formData['busId']);
        $departureId = intval($formData['departureId']);
        $arrivalId = intval($formData['arrivalId']);
        $dateTimeStart = DateTime::createFromFormat('Y-m-d\TH:i',$formData['dateTimeStart']);
        $dateTimeEnd = DateTime::createFromFormat('Y-m-d\TH:i',$formData['dateTimeEnd']);

        $trip = $this->viewData['tripRepo'][$id];
        $trip->setBusId($busId);
        $trip->setLocationStartId($departureId);
        $trip->setLocationEndId($arrivalId);
        $trip->setDateTimeStart($dateTimeStart);
        $trip->setDateTimeEnd($dateTimeEnd);

        $checkUpdate = TripRepository::update($trip);

        if($checkUpdate === false)
        {
            $res = new Response('Failure at update',403);
            $res->send();
            exit();
        }

        $res = new Response('Trip updated!',200);
        $res->send();
    }
}