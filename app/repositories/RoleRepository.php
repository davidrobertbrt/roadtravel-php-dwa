<?php

require_once '../app/model/Role.php';

final class RoleRepository{
    private function __construct() {}

    private static function getTableName() {return 'roles';}

    public static function readByUserId($userId)
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $stmt = $conn->prepare("SELECT * FROM {$table} WHERE userId = :userId");
        $stmt->bindParam(':userId',$userId,PDO::PARAM_INT);
        $checkExecute =  $stmt->execute();

        if($checkExecute === false)
        {
            $response = DatabaseConnection::getError($stmt->errorInfo());
            $response -> send();
            return null;
        }
        
        $resultDb = $stmt->fetch(PDO::FETCH_ASSOC) ?? null;

        if(!is_array($resultDb))
            return null;

        return Role::load
        (
            $resultDb['id'],intval($resultDb['userId']),$resultDb['role']
        );
    }


    public static function create(&$var) {

        if(!isset($var))
            return false;

        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $properties = $var->toArray();
        $values = array_values($properties);

        $placeholders = implode(',', array_fill(0, count($values), '?'));
        
        $stmt = $conn->prepare("INSERT INTO {$table} (userId, role) VALUES({$placeholders})");
        $stmt->execute($values);
        $var->setId($conn->lastInsertId());

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
}