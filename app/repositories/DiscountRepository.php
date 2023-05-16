<?php

require_once '../app/model/Discount.php';

class DiscountRepository
{
    private function __construct() {}

    private static function getTableName() {return 'discounts';}

    public static function readAll()
    {
        $conn = DatabaseConnection::getConnection();
        $table = self::getTableName();

        $stmt = $conn->query("SELECT * FROM {$table}");
        
        $resultDb = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $list = array();

        foreach($resultDb as $result)
        {
            $used = (int) filter_var($result['used'], FILTER_VALIDATE_BOOLEAN);
            $id = intval($result['id']);
            $factor = floatval($result['factor']);

            $list[$id] = new Discount(
                $id,$used,$factor  
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

        $used = (int) filter_var($resultDb['used'], FILTER_VALIDATE_BOOLEAN);
        $id = intval($resultDb['id']);
        $factor = floatval($resultDb['factor']);

        return new Discount(
            $id,$used,$factor  
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
        
        $stmt = $conn->prepare("INSERT INTO {$table} (factor,used) VALUES({$placeholders})");
        $stmt->execute($values);
        $var->setId($conn->lastInsertId());

        return true;
    }

    public static function update(&$var){
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