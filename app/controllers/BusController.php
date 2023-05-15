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
        $formData = $request->getData();
        $nrSeats = $formData['nrSeats'];

        $bus = new Bus(null,$nrSeats);
        $checkInsert = BusRepository::create($bus);
        if($checkInsert === false)
        {
            $response = new Response("Failure at insertion",403);
            $response->send();
        }
    }

    public function delete()
    {
        $formData = $request->getData();
        $id = $formData['id'];
        $bus = BusRepository::readById($id);

        $checkDelete = BusRepository::delete($bus);
        if($checkDelete === false)
        {
            $response = new Response("Failure at delete",403);
            $response->send();
        }
    }

    public function edit()
    {
        $formData = $request->getData();
        $id = $formData['id'];

        $bus = BusRepository::readById($id);
        $viewData['crBus'] = $bus;

        $this->render('BusEdit',$viewData);
    }

    public function process()
    {
        $formData = $request->getData();
        $id = $formData['id'];
        $nrSeats = $formData['nrSeats'];

        $bus = new Bus($id,$nrSeats);
        $bus->setNrSeats($nrSeats);

        $checkUpdate = BusRepository::update($bus);
        
        if($checkUpdate === false)
        {
            $response = new Response("Failure at updating",403);
            $response->send();
        }
    }
}