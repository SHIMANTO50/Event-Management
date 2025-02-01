<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=event_reports.csv');

// Fetch all events and attendees
$output = fopen("php://output", "w");
fputcsv($output, ['Event Name', 'Attendee Name']);

$stmt = $conn->prepare("SELECT e.id AS event_id, e.name AS event_name, u.name AS attendee_name
                            FROM attendees a
                            INNER JOIN events e ON a.event_id = e.id
                            INNER JOIN users u ON a.user_id = u.id");

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [ $row['event_name'], $row['attendee_name']]);
}

fclose($output);
exit;
?>
