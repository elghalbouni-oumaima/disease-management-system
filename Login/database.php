<?php
// Include Composer's autoloader 
require_once __DIR__ .'/../vendor/autoload.php'; 


use Dotenv\Dotenv;
// Load the .env file from the Login folder
$dotenv = Dotenv::createImmutable(__DIR__); 

//load variables
/*if (!$dotenv->safeLoad()) {
    die( "Erreur lors du chargement du fichier .env.\n");
} */

try {
    $dotenv->load();
} catch (Exception $e) {
    die("Erreur lors du chargement du fichier .env : " . $e->getMessage());
}

$server_name=$_ENV['DB_server_name'] ;
$user_name= $_ENV['DB_user_name'] ;
$password=$_ENV['DB_PASSWORD'];
$DB_name="diseasemanagement";

//create connection
$conn=new mysqli($server_name,$user_name,$password,$DB_name);

//chech the connection
if($conn->connect_error){
    die("connection failed:" . $conn->connect_error);
}
return $conn;
