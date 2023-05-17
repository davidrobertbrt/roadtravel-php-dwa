<?php

require_once '../app/config/DbConfig.php';

final class DatabaseConnection
{
    private static $dbConnection;

    public function __construct() {}

    public static function getConnection()
    {
        if(!isset(self::$dbConnection))
        {
            $db_host = DbConfig::$DB_HOST;
            $db_name = DbConfig::$DB_NAME;
            try{
                self::$dbConnection = new PDO("mysql:host={$db_host};dbname={$db_name}",DbConfig::$DB_USER,DbConfig::$DB_PASS);
                self::$dbConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
                echo "Database connection failure: " . $e->getMessage();
                exit;
            }
        }

        return self::$dbConnection;
    }

    public static function closeConnection()
    {
        if(isset($dbConnection))
            $dbConnection = null;
    }

}