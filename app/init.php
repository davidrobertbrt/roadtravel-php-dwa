<?php
session_start();
/**
 * Bootstraping for the modules responsable for the MVC Arhitecture.
 */
require_once 'core/Router.php';
require_once 'core/Request.php';
require_once 'core/Response.php';
require_once 'core/Controller.php';
require_once 'core/Middleware.php';
require_once 'core/DatabaseConnection.php';
require_once 'core/Cookie.php';
require_once 'core/GeolocationApi.php';
require_once 'core/WeatherApi.php';

/**
 * Not inclunded in mvc_project_mds
 */
require_once 'core/EmailSender.php';

/**
 * Repositories
 */

require_once 'repositories/CredentialRepository.php';
require_once 'repositories/UserRepository.php';
require_once 'repositories/LocationRepository.php';
require_once 'repositories/BusRepository.php';
require_once 'repositories/TripRepository.php';
require_once 'repositories/DiscountRepository.php';
require_once 'repositories/BookingRepository.php';
require_once 'repositories/RoleRepository.php';
require_once 'repositories/StatisticsRepository.php';

require_once 'vendor/fpdf/fpdf.php';

// debug statements
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$router = Router::getInstance();
$request = Request::parse();

$router->route($request);