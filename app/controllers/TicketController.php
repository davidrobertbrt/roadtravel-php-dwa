<?php

final class TicketController extends Controller
{
    private $busRepo;
    private $locationRepo;
    private $tripRepo;
    private $bookingRepo;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->busRepo = BusRepository::readAll();
        $this->locationRepo = LocationRepository::readAll();
        $this->tripRepo = TripRepository::readAll();
        $this->bookingRepo = BookingRepository::readAll();
    }

    public function index()
    {
        $userId = intval($_SESSION['user']);
        $bookings = BookingRepository::readByUser($userId);

        $results = array();

        foreach($bookings as $key => $value)
        {
            $id = $value->getId();
            $trip = $this->tripRepo[$value->getTripId()];

            $departureCity = $this->locationRepo[$trip->getLocationStartId()]->getName();
            $arrivalCity = $this->locationRepo[$trip->getLocationEndId()]->getName();
            $departureDate = $trip->getDateTimeStart();
            $arrivalDate = $trip->getDateTimeEnd();
            $noPersons = $value->getNumOfPersons();

            $results[] = array(
                'id'=>$id,
                'departureCity'=>$departureCity,
                'arrivalCity'=>$arrivalCity,
                'departureDate'=>$departureDate,
                'arrivalDate'=>$arrivalDate,
                'noPersons'=>$noPersons
            );
        }

        $this->viewData['results'] = $results;

        $this->render('TicketIndex',$this->viewData);
    }

    public function generate()
    {
        $formData = $this->request->getData();

        $bookingId = $formData['id'];
        $booking =  $this->bookingRepo[$bookingId];

        $tripId = $booking->getTripId();
        $userId = $booking->getUserId();
        
        $trip = $this->tripRepo[$tripId];
        $user = UserRepository::readById($userId);

        
    
        if(isset($trip) && isset($booking) && isset($user))
            $this->print($booking,$trip,$user);
        else
        {
            $response = new Response("Error",500);
            $response->send();
        }
        
    }

    private function print($booking,$trip,$user)
    {
        // Generate a unique ticket ID
        $ticketId = $booking->getId();
        $destinationId = $trip->getLocationEndId();

        // Trip information
        $destination = $this->locationRepo[$destinationId]->getName();
        $arrivalDate = $trip->getDateTimeEnd();

        // User information
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $address = $user->getAddress();
        $email = $user->getEmailAddress();
        $dateOfBirth = $user->getDateOfBirth()->format("Y-m-d");
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