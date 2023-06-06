<?php

class BusController extends Controller
{
    public function index()
    {
        $viewData['buses'] = BusRepository::readAll();
        $this->render('BusIndex',$viewData);
    }

    public function create()
    {
        $formData = $this->request->getData();
        $nrSeats = $formData['nrSeats'];

        if($nrSeats <= 0)
        {
            $response = new Response("You can't have negative or zero seats!",403);
            $response->send();
            exit();
        }

        $bus = new Bus(null,$nrSeats);
        $checkInsert = BusRepository::create($bus);
        if($checkInsert === false)
        {
            $response = new Response("Failure at insertion",403);
            $response->send();
            exit();
        }

        $response = new Response("Bus added",200);
        $response->send();
    }

    public function delete()
    {
        $formData = $this->request->getData();
        $id = $formData['id'];
        $bus = BusRepository::readById($id);

        if($bus === null)
        {
            $response = new Response("There is no bus with that id",500);
            $response->send();
            exit();
        }

        $countTrips = TripRepository::countByBus($bus->getId());

        if($countTrips > 0)
        {
            $response = new Response("There are trips associated with this bus!",500);
            $response->send();
            exit();
        }

        $checkDelete = BusRepository::delete($bus);
        if($checkDelete === false)
        {
            $response = new Response("Failure at delete",403);
            $response->send();
            exit();
        }

        $response = new Response("Bus deleted",200);
        $response->send();
    }

    public function edit()
    {
        $formData = $this->request->getData();
        $id = $formData['id'];

        $bus = BusRepository::readById($id);
        $viewData['crBus'] = $bus;

        $this->render('BusEdit',$viewData);
    }

    public function process()
    {
        $formData = $this->request->getData();
        $id = $formData['id'];
        $nrSeats = $formData['nrSeats'];

        if($nrSeats <= 0)
        {
            $response = new Response("You can't have negative or zero seats!",403);
            $response->send();
            exit();
        }

        if(BusRepository::readById($id) === null)
        {
            $response = new Response("There is no bus with that id",500);
            $response->send();
            exit();
        }

        if(BusRepository::checkNewSeats($id,$nrSeats) === false)
        {
            $response = new Response("Bookings are not valid for the number of seats provided!",403);
            $response->send();
            exit();   
        }

        $bus = new Bus($id,$nrSeats);
        $bus->setNrSeats($nrSeats);

        $checkUpdate = BusRepository::update($bus);
        
        if($checkUpdate === false)
        {
            $response = new Response("Failure at updating",403);
            $response->send();
            exit();
        }

        $response = new Response("Bus edited succesfully.",200);
        $response->send();
    }
}