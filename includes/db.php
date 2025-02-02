<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'event_management';

// Enable error reporting for MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Create connection
    $conn = new mysqli($host, $user, $password, $database);
    
    // Check connection (mysqli_report will catch errors automatically)
    if ($conn->connect_errno) {
        throw new Exception("Database Connection Failed: " . $conn->connect_error);
    }

} catch (Exception $e) {
    
    error_log($e->getMessage());

    die("Error: Unable to connect to the database. Please try again later.");
}
?>


