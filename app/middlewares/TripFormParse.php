<?php

final class TripFormParse implements Middleware
{
    public function __invoke($req)
    {
        $formData = $req->getData();
        $busId = $formData['busId'];
        $departureId = $formData['departureId'];
        $arrivalId = $formData['arrivalId'];
        $dateTimeStart = DateTime::createFromFormat('Y-m-d\TH:i',$formData['dateTimeStart']);
        $dateTimeEnd = DateTime::createFromFormat('Y-m-d\TH:i',$formData['dateTimeEnd']);

        if($departureId === $arrivalId)
        {
            $res = new Response("The arrival and departure are the same!",403);
            return $res;
        }

        if($dateTimeStart === $dateTimeEnd)
        {
            $res = new Response("The arrival and departure are the same!",403);
            return $res;
        }

        if($dateTimeStart > $dateTimeEnd)
        {
            $res = new Response("The date of the start is in future than the date of the end",403);
            return $res;
        }

        $formData['dateTimeStart'] = $dateTimeStart;
        $formData['dateTimeEnd'] = $dateTimeEnd;

        $req->setData($formData);
        return $req;
    }
}