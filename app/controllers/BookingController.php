<?php

class BookingController extends Controller
{
    public function __construct($request)
    {
        parent::__construct($request);
       
        $this->viewData['locationRepo'] = LocationRepository::readAll();
    }

    public function index()
    {
        $this->render('BookingIndex',$this->viewData);
    }

    public function process()
    {
        $formData = $this->request->getData();
        $tripId = $formData['trips'];
        $userId = $_SESSION['user'];
        $numOfPersons = $formData['persons'];
        $discountId = $formData['discount'];

        //if the discount is specified we will retrieve it.
        if(!empty($discountId))
            $discount = DiscountRepository::readById($discountId);

        $trip = TripRepository::readById($tripId);
        $departureId = $trip->getLocationStartId();
        $arrivalId = $trip->getLocationEndId();
        $departure = $this->viewData['locationRepo'][$departureId];
        $arrival = $this->viewData['locationRepo'][$arrivalId];

        $distance = GeolocationApi::calculateDistance($departure->getGeopos(),$arrival->getGeopos());
        $price = 2.3 * $distance * intval($numOfPersons);

        if(isset($discount))
            $price -= ($price * $discount->getFactor());

        $checkBooking = BookingRepository::readByUserTrip($userId,$tripId);

        if(isset($checkBooking))
        {
            $response = new Response('Booking already exists for this trip!',403);
            $response->send();
            exit();
        }

        $datePurchase = new DateTime();
        $datePurchase = $datePurchase->format('Y-m-d H:i:s');
        
        $booking = new Booking(null,$tripId,$userId,$numOfPersons,$price,$datePurchase);

        $checkCreate = BookingRepository::create($booking);

        if($checkCreate === false)
        {
            $response = new Response('Create operation failed!',403);
            $response->send();
            exit();
        }

        $response = new Response("Booking created",200);
        $response->send();
    }

    //API call through ajax
    public function fetchAvailableTrips()
    {
        $formData = $this->request->getData();
        // conversion should be made in the middleware
        $locationId = $formData['location']; 
        $date = $formData['date'];
        $time = $formData['time'];
        $formatDateTime = ($date.' '.$time);
        $dateTime = DateTime::createFromFormat('Y-m-d G:i',$formatDateTime);

        $tripList = TripRepository::fetchAvailable($locationId,$dateTime);

        if($tripList === null || empty($tripList))
        {
            echo "<option value=''>Nicio calatorie disponibila</option>";
            return;
        }

        $options = "<option value=''>Alege o ruta</option>";
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