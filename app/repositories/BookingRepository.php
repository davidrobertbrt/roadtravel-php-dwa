<?php

require_once '../app/model/Booking.php';

final class BookingRepository
{
    private function __construct() {}

    private static function getTableName() {return 'bookings';}

    public static function readAll()
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $stmt = $conn->query("SELECT * FROM {$table}");
        
        $resultDb = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $list = array();

        foreach($resultDb as $result)
        {
            $id = intval($result['id']);
            $tripId = intval($result['tripId']);
            $userId = intval($result['userId']);
            $numOfPersons = intval($result['numOfPersons']);
            $price = floatval($result['price']);
            $datePurchase = DateTime::createFromFormat('Y-m-d H:i:s',$result['datePurchase']);

            $list[$id] = new Booking(
                $id,$tripId,$userId,$numOfPersons,$price,$datePurchase
            );
        }

        return $list ?? null;
    }

    public static function readById($id)
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $stmt = $conn->prepare("SELECT * FROM {$table} WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
    
        $resultDb = $stmt->fetch(PDO::FETCH_ASSOC) ?? null;

        if(!is_array($resultDb))
            return null;

        $id = intval($resultDb['id']);
        $tripId = intval($resultDb['tripId']);
        $userId = intval($resultDb['userId']);
        $numOfPersons = intval($resultDb['numOfPersons']);
        $price = floatval($resultDb['price']);
        $datePurchase = DateTime::createFromFormat('Y-m-d H:i:s',$resultDb['datePurchase']);
        

        return new Booking(
            $id,$tripId,$userId,$numOfPersons,$price,$datePurchase
        );
    }

    public static function readByUserTrip($userId, $tripId)
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $stmt = $conn->prepare("SELECT * FROM {$table} WHERE userId = :userId AND tripId = :tripId");
        $stmt->bindParam(':userId',$userId,PDO::PARAM_INT);
        $stmt->bindParam(':tripId',$tripId,PDO::PARAM_INT);
        $stmt->execute();
    
        $resultDb = $stmt->fetch(PDO::FETCH_ASSOC) ?? null;

        if(!is_array($resultDb))
            return null;

        $id = intval($resultDb['id']);
        $tripId = intval($resultDb['tripId']);
        $userId = intval($resultDb['userId']);
        $numOfPersons = intval($resultDb['numOfPersons']);
        $price = floatval($resultDb['price']);
        $datePurchase = DateTime::createFromFormat('Y-m-d H:i:s',$resultDb['datePurchase']);
        

        return new Booking(
            $id,$tripId,$userId,$numOfPersons,$price,$datePurchase
        );
    }

    public static function countByTrip($tripId)
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();
        
        $stmt = $conn->prepare("SELECT COUNT(*) as numberOf FROM {$table} WHERE tripId = :tripId");
        $stmt->bindParam(':tripId', $tripId, PDO::PARAM_INT);
        
        $stmt->execute();
        $resultDb = $stmt->fetch(PDO::FETCH_ASSOC) ?? null;
        
        $bookingCount = intval($resultDb['numberOf']);
        
        return $bookingCount;
    }

    public static function readByUser($userId)
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();
    
        $stmt = $conn->prepare("SELECT * FROM {$table} WHERE userId = :userId");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
    
        $resultDb = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $list = array();
    
        foreach ($resultDb as $result) {
            $id = intval($result['id']);
            $tripId = intval($result['tripId']);
            $userId = intval($result['userId']);
            $numOfPersons = intval($result['numOfPersons']);
            $price = floatval($result['price']);
            $datePurchase = DateTime::createFromFormat('Y-m-d H:i:s', $result['datePurchase']);
    
            $list[$id] = new Booking(
                $id, $tripId, $userId, $numOfPersons, $price, $datePurchase
            );
        }
    
        return $list ?? null;
    }
    

    public static function create(&$var)
    {
        if(!isset($var))
            return false;

        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $properties = $var->toArray();
        $values = array_values($properties);

        $placeholders = implode(',', array_fill(0, count($values), '?'));
        
        $stmt = $conn->prepare("INSERT INTO {$table} (tripId,userId,numOfPersons,price,datePurchase) VALUES({$placeholders})");
        $stmt->execute($values);
        $var->setId($conn->lastInsertId());

        return true;
    }

    public static function delete(&$var)
    {
        if(!isset($var))
        return false;

        if($var->getId() === null)
            return false;

        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();
        $id = $var->getId();
        $stmt = $conn->prepare("DELETE FROM {$table} WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        
        $checkDelete = $stmt->execute();

        if($checkDelete === false)
            return false;

        unset($var);
        return true;
    }

    public static function update(&$var)
    {
        if(!isset($var))
            return false;

        if($var->getId() === null)
            return false;

        $id = $var->getId();
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $properties = $var->toArray();

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
}