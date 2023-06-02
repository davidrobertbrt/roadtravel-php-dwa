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

    /**
     * Returns a response. it should be handled in the appropriate classes
     */

    public static function getError($errorInfo)
    {
        $sqlState = $errorInfo[0];
        $driverCode = $errorInfo[1];
        $driverMessage = $errorInfo[2];
    
        $errorMessage = "<p>An error occurred:</p>";
        $errorMessage .= "<p>SQLSTATE: $sqlState</p>";
        $errorMessage .= "<p>Code: $driverCode</p>";
        $errorMessage .= "<p>Message: $driverMessage</p>";

        $response = new Response($errorMessage,500);
        return $response;
    }

}