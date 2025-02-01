<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Handle delete request
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM events WHERE id = ? AND created_by = ?");
    $stmt->bind_param("ii", $event_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
