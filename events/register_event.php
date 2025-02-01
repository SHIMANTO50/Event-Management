<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("You must log in first.");
}

$user_id = $_SESSION['user_id'];
$event_id = $_POST['event_id'];

// Check if user is registering for their own event
$query = "SELECT created_by, capacity FROM events WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id); 
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    die("Event not found.");
}

if ($event['created_by'] == $user_id) {
    die("You cannot register for your own event.");
}

// Check if the event is full
$query = "SELECT COUNT(*) AS total FROM attendees WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$registered_count = $result->fetch_assoc()['total'];

if ($registered_count >= $event['capacity']) {
    die("Registration failed! This event is full.");
}

// Register the user
$query = "INSERT INTO attendees (user_id, event_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $event_id); // "ii" denotes two integer parameters
if ($stmt->execute()) {
    echo "Registration successful!<br><a href='allevents.php'>Back to Dashboard</a>";
} else {
    echo "Registration failed!";
}

$stmt->close();
$conn->close();
?>
