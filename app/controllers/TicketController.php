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
            $trip = TripRepository::readById($value->getTripId());

            if($trip === null)
            {
                $res = new Response('There is no trip with that ID!',403);
                $res->send();
                exit();
            }
            $departureCity = LocationRepository::readById($trip->getLocationStartId())->getName();
            $arrivalCity = LocationRepository::readById($trip->getLocationEndId())->getName();
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
        $booking = BookingRepository::readById($bookingId);

        if($booking === null)
        {
            $res = new Response('There is no booking with that ID!',403);
            $res->send();
            exit();
        }

        $tripId = $booking->getTripId();
        $userId = $booking->getUserId();
        
        $trip = TripRepository::readById($tripId);
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
        $dateOfBirth = $user->getDateOfBirth();
        $phoneNumber = $user->getPhoneNumber();

        // Ticket details
        $price = $booking->getPrice();
        $purchaseDate = $booking->getDatePurchase();

        $busId = $trip->getBusId();
        // Bus ID
        $busNo = "BUS{$busId}";

        // Create PDF object
        $pdf = new FPDF();

        // Add a page
        $pdf->AddPage();

        // Set font and size for the title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Bilet pentru autobuz', 0, 1, 'C');

        // Set font and size for the ticket ID
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Cod bilet: ' . $ticketId, 0, 1, 'C');

        // Add line breaks
        $pdf->Ln(10);

        // Set font and size for the section headings
        $pdf->SetFont('Arial', 'B', 12);

        // Print trip information
        $pdf->Cell(0, 10, 'Informatii calatorie', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Destinatie: ' . $destination, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Data sosirii: ' . $arrivalDate, 0, 1, 'L');

        // Add line breaks
        $pdf->Ln(10);

        // Print user information
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Informatii despre client', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Nume: ' . $firstName . ' ' . $lastName, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Adresa: ' . $address, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Email: ' . $email, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Data nasterii: ' . $dateOfBirth, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Numar de telefon: ' . $phoneNumber, 0, 1, 'L');

        // Add line breaks
        $pdf->Ln(10);

        // Print ticket details
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Detalii bilet', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Pret: ' . $price, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Data cumpararii: ' . $purchaseDate->format('Y-m-d'), 0, 1, 'L');

        // Add line breaks
        $pdf->Ln(10);

        // Print bus ID
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Cod autobuz: ' . $busNo, 0, 1, 'L');

        // Output the PDF
        $pdf->Output();
    }
}