<?php

require_once '../app/model/Bus.php';

final class BusRepository
{
    private function __construct() {}

    public static function getTableName()
    { return 'buses'; }

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

        return new Bus($resultDb['id'],$resultDb['nrSeats']);
    }

    public static function readAll()
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $stmt = $conn->query("SELECT * FROM {$table}");
        $resultDb = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $busList = array();

        foreach($resultDb as $result)
        {
            $busList[$result['id']] = new Bus(
                $result['id'],$result['nrSeats']
            );
        }

        return $busList ?? null;
    }

    public static function checkNewSeats($busId,$newSeats)
    {
        $pdo = DatabaseConnection::getConnection();
        $sql = "CALL CheckBookingValidityForBus(:busId, :newSeats, @isValidResult)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busId', $busId, PDO::PARAM_INT);
        $stmt->bindParam(':newSeats', $newSeats, PDO::PARAM_INT);

        $stmt->execute();

        $stmt = $pdo->query("SELECT @isValidResult");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $isValid = $result['@isValidResult'];

        return (bool)$isValid;
    }

    public static function create(&$bus)
    {
        if(!isset($bus))
            return false;
        
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $properties = $bus->toArray();
        $values = array_values($properties);

        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $stmt = $conn->prepare("INSERT INTO {$table} (nrSeats) VALUES({$placeholders})");
        
        $checkInsert = $stmt->execute($values);

        if($checkInsert === false)
            return false;

        $bus->setId($conn->lastInsertId());
        return true;
    }

    public static function update(&$bus)
    {
        if(!isset($bus))
            return false;

        if($bus->getId() === null)
            return false;

        $id = $bus->getId();
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $properties = $bus->toArray();
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

    public static function delete(&$bus)
    {
        if(!isset($bus))
            return false;

        if($bus->getId() === null)
            return false;

        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();
        $id = $bus->getId();
        $stmt = $conn->prepare("DELETE FROM {$table} WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        
        $checkDelete = $stmt->execute();

        if($checkDelete === false)
            return false;

        unset($bus);
        return true;
    }
}