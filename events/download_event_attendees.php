<?php
session_start();
include '../includes/db.php';

if (isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);

    // Fetch event details
    $event_query = "SELECT name FROM events WHERE id = $event_id";
    $event_result = mysqli_query($conn, $event_query);

    if (!$event_result || mysqli_num_rows($event_result) == 0) {
        $_SESSION['error'] = "Invalid event ID.";
        header("Location: admin_dashboard.php"); // Redirect back
        exit();
    }

    $event = mysqli_fetch_assoc($event_result);
    $event_name = $event['name'];

    // Fetch attendees with user details
    $query = "
        SELECT users.name, users.email 
        FROM attendees 
        INNER JOIN users ON attendees.user_id = users.id
        WHERE attendees.event_id = $event_id
    ";
    
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename={$event_name}_attendees.csv");

        // Open output stream
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Name', 'Email']); // CSV Header

        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, [$row['name'], $row['email']]);
        }

        fclose($output);
        exit();
    } else {
        $_SESSION['error'] = "No attendees found for this event.";
        header("Location: admin_dashboard.php"); // Redirect back
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid event ID.";
    header("Location: admin_dashboard.php"); // Redirect back
    exit();
}
?>
