<?php

class TripController extends Controller
{
    public function index()
    {
        $locationRepo = new LocationRepository();
        $viewData['busRepo'] = BusRepository::readAll();
        $viewData['locationRepo'] = $locationRepo->readAll();
        $viewData['tripRepo'] = TripRepository::readAll();

        $this->render('TripIndex',$viewData);
    }

    public function create()
    {
        
    }

    public function delete()
    {

    }

    public function edit()
    {

    }

    public function process()
    {

    }
}