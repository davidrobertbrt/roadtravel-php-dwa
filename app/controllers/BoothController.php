<?php 

class BoothController extends Controller
{
    public function index()
    {
        $bookings = BookingRepository::readAll();

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

            $user = UserRepository::readById($value->getUserId());

            $results[] = array(
                'id'=>$id,
                'email'=>$user->getEmailAddress(),
                'departureCity'=>$departureCity,
                'arrivalCity'=>$arrivalCity,
                'departureDate'=>$departureDate,
                'arrivalDate'=>$arrivalDate,
                'noPersons'=>$noPersons
            );
        }

        $this->viewData['results'] = $results;

        $this->render('BoothIndex',$this->viewData);
    }

    public function delete()
    {
        $formData = $this->request->getData();
        $id = $formData['id'];

        $booking = BookingRepository::readById($id);

        if($booking === null)
        {
            $response = new Response('Booking does not exist',403);
            $response->send();
            return;
        }

        $checkDelete = BookingRepository::delete($booking);

        if($checkDelete === false)
        {
            $response = new Response('Error at deletion',500);
            $response->send();
            return;
        }

        $response = new Response('Deleted booking successfully!',200);
        $response->send();
    }
}