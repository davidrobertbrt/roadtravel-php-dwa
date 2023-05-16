<?php

require_once '../app/model/Location.php';

class LocationRepository{

    public function __construct() {}

    public static function getTableName() {return 'locations';}

    public function readById($id)
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $stmt = $conn->prepare("SELECT * FROM {$table} WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->execute();

        $resultDb = $stmt->fetch(PDO::FETCH_ASSOC) ?? null;

        if(!is_array($resultDb))
            return null;

        return Location::loadByParams(
            $resultDb['id'],$resultDb['name'],$resultDb['longitude'],$resultDb['latitude']
        );
    }

    public function readByName($name)
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $stmt = $conn->prepare("SELECT * FROM {$table} WEHERE name = :name");
        $stmt->bindParam(':name',$name,PDO::PARAM_STR);

        $resultDb = $stmt->fetch(PDO::FETCH_ASSOC) ?? null; 

        if(!is_array($resultDb))
            return null;

        return Location::loadByParams(
            $resultDb['id'],$resultDb['name'],$resultDb['longitude'],$resultDb['latitude']
        );
    }

    public function readAll()
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $stmt = $conn->query("SELECT * FROM {$table}");
        
        $resultDb = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $locationList = array();


        foreach($resultDb as $result)
        {
            $locationList[$result['id']] = Location::loadByParams(
                $result['id'],$result['name'],$result['longitude'],$result['latitude']
            );
        }

        return $locationList ?? null;
    }

    public function create($location)
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $properties = $location->toArray();
        $values = array_values($properties);

        $checkLocation = $this->readByName($properties['name']);

        if(isset($checkLocation))
            return null;

        $placeholders = implode(',', array_fill(0, count($values), '?'));

        $stmt = $conn->prepare("INSERT INTO {$table} (name,longitude,latitude) VALUES({$placeholders})");
        $stmt->execute($values);

        return $conn->lastInsertId();
    }

    public function update($location)
    {
        if($location->getId() === null)
            return null;

        $id = $location->getId();
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $properties = $location->toArray();
        $set = implode(' = ?, ',array_keys($properties)) . ' = ? ';
        $values = array_values($properties);
        $values[] = $id;

        $stmt = $conn->prepare("UPDATE {$table} SET {$set} WHERE id = ?");

        return $stmt->execute($values);
        
    }

    public function delete($location)
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();
        $id = $location->getId();
        $stmt = $conn->prepare("DELETE FROM {$table} WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        return $stmt->execute();
    }

}