<?php

class BookingController extends Controller
{
    public function __construct($request)
    {
        parent::__construct($request);
        $locationRepo = new LocationRepository();
        $this->viewData['locationRepo'] = $locationRepo->readAll();
    }

    public function index()
    {
        $this->render('BookingIndex',$this->viewData);
    }

    public function fetchAvailableTrips()
    {
        $formData = $this->request->getData();
        $locationId = intval($formData['location']);
        $date = $formData['date'];
        $time = $formData['time'];
        $formatDateTime = ($date.' '.$time);
        $dateTime = DateTime::createFromFormat('Y-m-d G:i',$formatDateTime);

        $tripList = TripRepository::fetchAvailable($locationId,$dateTime);

        if($tripList === null || empty($tripList))
        {
            echo "<option value=''>No trips available</option>";
            return;
        }

        $options = "<option value=''>Select a trip</option>";
        foreach($tripList as $trip)
        {
            //"LocationStart: DateTimeStart -- LocationEnd: DateTimeEnd "
            $tripId = $trip->getId();
            $departure = $this->viewData['locationRepo'][$trip->getLocationStartId()]->getName();
            $arrival = $this->viewData['locationRepo'][$trip->getLocationEndId()]->getName();
            $dateTimeStart = $trip->getDateTimeStart();
            $dateTimeEnd = $trip->getDateTimeEnd();
            $optionString = "{$departure}: {$dateTimeStart} -- {$arrival}: {$dateTimeEnd}";

            $options .= "<option value='$tripId'>$optionString</option>";
        }

        echo $options;

    }
}