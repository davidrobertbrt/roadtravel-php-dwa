<?php

require_once '../app/model/Trip.php';

class TripRepository
{
    // utility class
    private function construct() {}

    public static function getTableName() {return 'trips';}

    public static function readById($id) {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $stmt = $conn->prepare("SELECT * FROM {$table} WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
    
        $resultDb = $stmt->fetch(PDO::FETCH_ASSOC) ?? null;

        if(!is_array($resultDb))
            return null;

        return new Trip
        (
            $resultDb['id'],$resultDb['busId'],$resultDb['locationStartId'],$resultDb['locationEndId'],$resultDb['dateTimeStart'],$resultDb['dateTimeEnd']
        );
    }

    public static function readByBusId($busId) {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $stmt = $conn->prepare("SELECT * FROM {$table} WHERE busId = :busId");
        $stmt->bindParam(':busId',$busId,PDO::PARAM_INT);
        $stmt->execute();
    
        $resultDb = $stmt->fetch(PDO::FETCH_ASSOC) ?? null;

        if(!is_array($resultDb))
            return null;

        return new Trip
        (
            $resultDb['id'],$resultDb['bus'],$resultDb['locationStartId'],$resultDb['locationEndId'],$resultDb['dateTimeStart'],$resultDb['dateTimeEnd']
        );
    }

    //TO-DO: function to fetch the available trips based on bookings 
    public static function fetchAvailable($locationStart, $locationEnd,$dateTimeStart,$dateTimeEnd) {
        
    }

    public static function readAll() {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $stmt = $conn->query("SELECT * FROM {$table}");
        
        $resultDb = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $list = array();

        foreach($resultDb as $result)
        {
            $list[$result['id']] = new Trip(
                $result['id'],$result['busId'],$result['locationStartId'],$result['locationEndId'],$result['dateTimeStart'],$result['dateTimeEnd']
            );
        }

        return $list ?? null;
    }

    
    // for this i think we should check if there is any other course on the date, bus specified 
    // let's just check that either the $newDateStart >= $otherTripEnd
    // and also let's check that either the $newDateEnd <= $otherTripStart
    // so we need to find which dates have any of this property.
    // if any one of them has this property, we should probably stop and do not insert.
    // and also to be the same bus we are refering so we aren't alternating the timeline, but that we should check AFTER 
    // so we need to find in the database the ones which are
    // $searchTripEnd <= $newDateStart
    // OR
    // $searchTripStart >= $newDateEnd
    // if we do this, we should be good, we should not have any count of them.
    public static function canInsertTrip($tripId,$busId, $dateStart, $dateEnd)
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();
    
        if(isset($tripId))
        {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM {$table} WHERE ((dateTimeStart <= :dateEnd AND dateTimeEnd >= :dateStart) OR (dateTimeStart <= :dateStart AND dateTimeEnd >= :dateEnd)) AND (busId = :busId) AND (id != :tripId)");
            $stmt->bindParam(':tripId',$tripId);
        }
        else
            $stmt = $conn->prepare("SELECT COUNT(*) FROM {$table} WHERE ((dateTimeStart <= :dateEnd AND dateTimeEnd >= :dateStart) OR (dateTimeStart <= :dateStart AND dateTimeEnd >= :dateEnd)) AND (busId = :busId)");

        $stmt->bindParam(':dateStart', $dateStart);
        $stmt->bindParam(':dateEnd', $dateEnd);
        $stmt->bindParam(':busId', $busId);
        $stmt->execute();
    
        $rowCount = $stmt->fetchColumn();
    
        return ($rowCount === 0);
    }
    

    public static function create(&$trip) {

        if(!isset($trip))
            return false;

        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $properties = $trip->toArray();
        $values = array_values($properties);

        $checkTrip = self::canInsertTrip(null,$properties['busId'],$properties['dateTimeStart'],$properties['dateTimeEnd']);

        if($checkTrip === false)
            return false;

        $placeholders = implode(',', array_fill(0, count($values), '?'));
        
        $stmt = $conn->prepare("INSERT INTO {$table} (busId,locationStartId,locationEndId,dateTimeStart,dateTimeEnd) VALUES({$placeholders})");
        $stmt->execute($values);
        $trip->setId($conn->lastInsertId());

        return true;
    }

    public static function update(&$trip) {
        if(!isset($trip))
            return false;

        if($trip->getId() === null)
            return false;

        $id = $trip->getId();
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $properties = $trip->toArray();

        $checkTrip = self::canInsertTrip($id,$properties['busId'],$properties['dateTimeStart'],$properties['dateTimeEnd']);

        if($checkTrip === false)
            return false;

        $set = implode(' = ?, ',array_keys($properties)) . ' = ? ';
        $values = array_values($properties);
        $values[] = $id;

        $stmt = $conn->prepare(
            "UPDATE {$table} SET {$set} WHERE id = ?"
        );

        $checkUpdate = $stmt->execute($values);

        if($checkUpdate === false)
            return false;

        return true;
    }

    public static function delete(&$trip) {
        if(!isset($trip))
            return false;

        if($trip->getId() === null)
            return false;

        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();
        $id = $trip->getId();
        $stmt = $conn->prepare("DELETE FROM {$table} WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        
        $checkDelete = $stmt->execute();

        if($checkDelete === false)
            return false;

        unset($trip);
        return true;
    }
}