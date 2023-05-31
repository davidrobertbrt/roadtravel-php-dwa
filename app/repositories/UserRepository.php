<?php

require_once '../app/model/User.php';

final class UserRepository
{
    private function __construct() {}

    public static function getTableName()
    {
        return "users";
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

        $resultDb['dateOfBirth'] = DateTime::createFromFormat('Y-m-d H:i:s',$resultDb['dateOfBirth']);


        return User::loadByParams
        (
            $resultDb['id'],$resultDb['emailAddress'],$resultDb['firstName'],$resultDb['lastName'],$resultDb['dateOfBirth'],$resultDb['phoneNumber'],$resultDb['address']
        );
    }

    public static function readByEmail($emailAddress)
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $stmt = $conn->prepare("SELECT * FROM {$table} WHERE emailAddress = :emailAddress");
        $stmt->bindParam(':emailAddress',$emailAddress,PDO::PARAM_STR);
        $stmt->execute();

        
        $resultDb = $stmt->fetch(PDO::FETCH_ASSOC) ?? null;

        if(!is_array($resultDb))
            return null;

        $resultDb['dateOfBirth'] = DateTime::createFromFormat('Y-m-d H:i:s',$resultDb['dateOfBirth']);


        return User::loadByParams
        (
            $resultDb['id'],$resultDb['emailAddress'],$resultDb['firstName'],$resultDb['lastName'],$resultDb['dateOfBirth'],$resultDb['phoneNumber'],$resultDb['address']
        );
    }


    public static function create($user)
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();
    
        $properties = $user->toArray();
        $values = array_values($properties);
    
        $checkUser = self::readByEmail($properties['emailAddress']);
    
        if ($checkUser !== null) {
            return null;
        }
    
        $placeholders = implode(',', array_fill(0, count($values), '?'));
    
        $stmt = $conn->prepare("INSERT INTO {$table} (firstName, lastName, dateOfBirth, phoneNumber, address, emailAddress) VALUES ({$placeholders})");
        $stmt->execute($values);
    
        return $conn->lastInsertId();
    }
    

    public static function updateById($user)
    {
        if($user->getId() === null)
            return null;

        $id = $user->getId();

        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $properties = $user->toArray();

        $set = implode(' = ?, ',array_keys($properties)) . ' = ? ';
        $values = array_values($properties);
        $values[] = $id;
        $stmt = $conn->prepare("UPDATE {$table} SET {$set} WHERE id = ?");

        return $stmt->execute($values);
    }

    public static function delete($user)
    {
        $id = $user->getId();
        $stmt = $conn->prepare("DELETE FROM {$table} WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        return $stmt->execute();
    }

}