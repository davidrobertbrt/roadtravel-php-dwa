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

    //API call through ajax
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
        return;
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
        {
            $discountId = intval($discountId);
            $discount = DiscountRepository::readById($discountId);
        }

        $trip = TripRepository::readById($tripId);
        $departureId = $trip->getLocationStartId();
        $arrivalId = $trip->getLocationEndId();
        $departure = $this->viewData['locationRepo'][$departureId];
        $arrival = $this->viewData['locationRepo'][$arrivalId];

        $distance = GeolocationApi::calculateDistance($departure->getGeopos(),$arrival->getGeopos());
        $price = 2.3 * $distance * intval($numOfPersons);

        if(isset($discount))
            $price -= ($price * $discount->getFactor());

        $datePurchase = new DateTime();
        $datePurchase = $datePurchase->format('Y-m-d H:i:s');

        $checkBooking = BookingRepository::readByUserTrip($userId,$tripId);

        if(isset($checkBooking))
        {
            $response = new Response('Booking already exists for this trip!',403);
            $response->send();
            exit();
        }
        
        $booking = new Booking(null,$tripId,$userId,$numOfPersons,$price,$datePurchase);

        $checkCreate = BookingRepository::create($booking);

        if($checkCreate === false)
        {
            $response = new Response('Create operation failed!',403);
            $response->send();
            exit();
        }

        $user = UserRepository::readById($userId);
        $this->print($booking,$trip,$user);
        return;
    }

    private function print($booking,$trip,$user)
    {
        // Generate a unique ticket ID
        $ticketId = $booking->getId();
        $destinationId = $trip->getLocationEndId();

        // Trip information
        $destination = $this->viewData['locationRepo'][$destinationId]->getName();
        $arrivalDate = $trip->getDateTimeEnd();

        // User information
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $address = $user->getAddress();
        $email = $user->getEmailAddress();
        $dateOfBirth = $user->getDateOfBirth();
        $phoneNumber = $user->getPhoneNumber();

        // Ticket details
        $price = $booking->getPrice();
        $purchaseDate = date('Y-m-d');

        $busId = $trip->getBusId();
        // Bus ID
        $busNo = "BUS{$busId}";

        // Create PDF object
        $pdf = new FPDF();

        // Add a page
        $pdf->AddPage();

        // Set font and size for the title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Bus Ticket', 0, 1, 'C');

        // Set font and size for the ticket ID
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Ticket ID: ' . $ticketId, 0, 1, 'C');

        // Add line breaks
        $pdf->Ln(10);

        // Set font and size for the section headings
        $pdf->SetFont('Arial', 'B', 12);

        // Print trip information
        $pdf->Cell(0, 10, 'Trip Information', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Destination: ' . $destination, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Arrival Date: ' . $arrivalDate, 0, 1, 'L');

        // Add line breaks
        $pdf->Ln(10);

        // Print user information
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'User Information', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Name: ' . $firstName . ' ' . $lastName, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Address: ' . $address, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Email: ' . $email, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Date of Birth: ' . $dateOfBirth, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Phone Number: ' . $phoneNumber, 0, 1, 'L');

        // Add line breaks
        $pdf->Ln(10);

        // Print ticket details
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Ticket Details', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Price: ' . $price, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Purchase Date: ' . $purchaseDate, 0, 1, 'L');

        // Add line breaks
        $pdf->Ln(10);

        // Print bus ID
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Bus ID: ' . $busNo, 0, 1, 'L');

        // Output the PDF
        $pdf->Output();
    }
}