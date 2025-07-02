<?php

// Include Composer's autoloader 
require_once __DIR__ .'/../vendor/autoload.php'; 

use Dotenv\Dotenv;
// Load the .env file from the Login folder
$dotenv = Dotenv::createImmutable(__DIR__); 

//load variables
if (!$dotenv->safeLoad()) {
    die( "Erreur lors du chargement du fichier .env mailer.\n");
} 

// Use PHPMailer classes for email sending functionality
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Set the mailer to use SMTP
    $mail->isSMTP();

    // Enable SMTP authentication
    $mail->SMTPAuth = true;

    // Set the SMTP server to send emails through
    $mail->Host = "smtp.gmail.com";  // Enter the SMTP server

    // Enable TLS encryption for secure communication
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    // Specify the SMTP port for the server (587 is commonly used for TLS)
    $mail->Port = 587;

    // SMTP server authentication username
    $mail->Username =  $_ENV['SMTP_USERNAME'];;  // Enter the SMTP server username 

    // SMTP server authentication password
    $mail->Password = $_ENV['App_password'];  // Enter the password for the SMTP server

    // Set the mailer to use HTML format
    $mail->isHTML(true);

    // Return the PHPMailer object to allow further manipulation or sending
    return $mail;
} catch (Exception $e) {
    // Handle error if something goes wrong
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

