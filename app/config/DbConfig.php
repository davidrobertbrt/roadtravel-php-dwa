<?php
/**
 * File used to configure the database connection. 
 * For the data-access abstractization layer we will use PHP PDO with MySql engine.
 * Change the DB_HOST,DB_NAME,DB_USER,DB_PASS to your own configuration. 
 */

class DbConfig{
    public static $DB_HOST = "localhost";
    public static $DB_NAME = "roadtravel";
    public static $DB_USER = "simplemvc";
    public static $DB_PASS = "asimplemvc";
}