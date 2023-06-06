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

        if(BusRepository::readById($formData['busId']) === null)
        {
            $res = new Response('There is no bus with that ID!',403);
            $res->send();
            exit();
        }

        if(LocationRepository::readById($formData['departureId']) === null)
        {
            $res = new Response('There is no location with that ID!',403);
            $res->send();
            exit();
        }

        if(LocationRepository::readById($formData['arrivalId']) === null)
        {
            $res = new Response('There is no location with that ID!',403);
            $res->send();
            exit();
        }

        $busId = $formData['busId'];
        $departureId = $formData['departureId'];
        $arrivalId = $formData['arrivalId'];
        $dateTimeStart = $formData['dateTimeStart']->format('Y-m-d H:i:s');
        $dateTimeEnd = $formData['dateTimeEnd']->format('Y-m-d H:i:s');

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
        $trip = TripRepository::readById($id);

        $checkBookings = BookingRepository::countByTrip($trip->getId());

        if($checkBookings > 0)
        {
            $res = new Response("For this trip exists bookings. You can't delete it before deleting the bookings associated with it!",500);
            $res->send();
            exit();
        }

        if($trip === null)
        {
            $res = new Response('There is no location with that ID!',403);
            $res->send();
            exit();
        }

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
        $trip = TripRepository::readById($id);

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

        if(BusRepository::readById($formData['busId']) === null)
        {
            $res = new Response('There is no bus with that ID!',403);
            $res->send();
            exit();
        }

        if(LocationRepository::readById($formData['departureId']) === null)
        {
            $res = new Response('There is no location with that ID!',403);
            $res->send();
            exit();
        }

        if(LocationRepository::readById($formData['arrivalId']) === null)
        {
            $res = new Response('There is no location with that ID!',403);
            $res->send();
            exit();
        }

        $busId = $formData['busId'];
        $departureId = $formData['departureId'];
        $arrivalId = $formData['arrivalId'];
        $dateTimeStart = $formData['dateTimeStart']->format('Y-m-d H:i:s');
        $dateTimeEnd = $formData['dateTimeEnd']->format('Y-m-d H:i:s');

        $trip = TripRepository::readById($id);

        if($trip === null)
        {
            $res = new Response('There is no trip with that ID!',403);
            $res->send();
            exit();
        }

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