<?php

require_once '../app/model/Credential.php';

class CredentialRepository
{
    // prevent construct of utility class
    private function __construct() {}

    public static function getTableName()
    {
        return "credentials";
    }

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


        return Credential::loadByParams
        (
            $resultDb['id'],$resultDb['userId'],$resultDb['password']
        );
    }

    public function readByUserId($userId)
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


    public function create($credential)
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $values = array_values($credential->toArray());

        $checkUser = (readByUserId($values['userId'])) ? true : false;

        if($checkUser === true)
            return null;

        $placeholders = array_fill(0,count($data),'?');

        $stmt = $conn->prepare("INSERT INTO {$table} (id,userId,password) VALUES({$placeholders})");
        $stmt->execute($values);

        return $conn->lastInsertedId();
    }

    public function updateById($credential)
    {
        if($user->getId() === null)
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

    public function delete($credential)
    {
        $id = $credential->getId();
        $stmt = $conn->prepare("DELETE FROM {$table} WEHERE id = :id");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        return $stmt->execute();
    }
}