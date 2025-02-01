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

    // Connection successful
    //echo "Database connected successfully.";
} catch (Exception $e) {
    // Log the error (optional)
    error_log($e->getMessage());

    // Display a user-friendly error message
    die("Error: Unable to connect to the database. Please try again later.");
}
?>


