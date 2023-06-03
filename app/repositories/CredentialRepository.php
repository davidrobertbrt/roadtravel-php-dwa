<?php

require_once '../app/model/Credential.php';

final class CredentialRepository
{
    // prevent construct of utility class
    private function __construct() {}

    public static function getTableName()
    {
        return "credentials";
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


        return Credential::loadByParams
        (
            $resultDb['id'],$resultDb['userId'],$resultDb['password']
        );
    }

    public static function readByUserId($userId)
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $stmt = $conn->prepare("SELECT * FROM {$table} WHERE userId = :userId");
        $stmt->bindParam(':userId',$userId,PDO::PARAM_INT);
        $stmt->execute();

        
        $resultDb = $stmt->fetch(PDO::FETCH_ASSOC) ?? null;

        if(!is_array($resultDb))
            return null;


        return Credential::loadByParams
        (
            $resultDb['id'],$resultDb['userId'],$resultDb['password']
        );
    }


    public static function create(&$credential)
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $properties = $credential->toArray();
        $values = array_values($credential->toArray());

        $checkUser = (self::readByUserId($properties['userId'])) ? true : false;

        if($checkUser === true)
            return null;

        $placeholders = implode(',', array_fill(0, count($values), '?'));

        $stmt = $conn->prepare("INSERT INTO {$table} (userId,password) VALUES({$placeholders})");
        $checkExecute = $stmt->execute($values);

        if($checkExecute === false)
        {
            $response = DatabaseConnection::getError($checkExecute);
            $response->send();
            return false;
        }

        $credential->setId($conn->lastInsertId());
        return true;
    }

    public static function update($credential)
    {
        if($credential->getId() === null)
            return null;

        $id = $credential->getId();

        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $properties = $credential->toArray();

        $set = implode(' = ?, ',array_keys($properties)) . ' = ? ';
        $values = array_values($properties);
        $values[] = $id;
        $stmt = $conn->prepare("UPDATE {$table} SET {$set} WHERE id = ?");

        return $stmt->execute($values);
    }

    public static function delete(&$credential)
    {
        $id = $credential->getId();
        $stmt = $conn->prepare("DELETE FROM {$table} WHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $checkExecute =  $stmt->execute();

        if($checkExecute === false)
        {
            $response = DatabaseConnection::getError($checkExecute);
            $response->send();
            return false;
        }

        unset($credential);
        return true;
    }
}