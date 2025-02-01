<?php
session_start();
include '../includes/db.php'; 

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; 
$offset = ($page - 1) * $limit;

// Base query
$query = "SELECT id, name, description, capacity, created_by FROM events WHERE created_by != ?";
$params = ["i", $userId];

if (!empty($search)) {
    $query .= " AND (name LIKE ? OR description LIKE ?)";
    $searchTerm = "%" . $search . "%";
    $params[0] .= "ss";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

// Add ORDER BY before pagination
$query .= " ORDER BY id DESC";

// Get total record count for pagination
$totalQuery = "SELECT COUNT(*) FROM events WHERE created_by != ?";
$totalParams = ["i", $userId];

if (!empty($search)) {
    $totalQuery .= " AND (name LIKE ? OR description LIKE ?)";
    $totalParams[0] .= "ss";
    $totalParams[] = $searchTerm;
    $totalParams[] = $searchTerm;
}

$totalStmt = $conn->prepare($totalQuery);
if (!$totalStmt) {
    die("Error preparing statement: " . $conn->error);
}

$totalStmt->bind_param(...array_merge([$totalParams[0]], array_slice($totalParams, 1)));
$totalStmt->execute();
$totalStmt->bind_result($totalRecords);
$totalStmt->fetch();
$totalStmt->close();

// Apply pagination limits
$query .= " LIMIT ?, ?";
$params[0] .= "ii";
$params[] = $offset;
$params[] = $limit;

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param(...array_merge([$params[0]], array_slice($params, 1)));
$stmt->execute();
$result = $stmt->get_result();

// Output table
$output = '<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Event Name</th>
            <th>Description</th>
            <th>Capacity</th>
            <th>Remaining Slots</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>';

if ($result->num_rows > 0) {
    while ($event = $result->fetch_assoc()) {
        // Get the number of registered attendees
        $attendeeStmt = $conn->prepare('SELECT COUNT(*) FROM attendees WHERE event_id = ?');
        $attendeeStmt->bind_param('i', $event['id']);
        $attendeeStmt->execute();
        $attendeeStmt->bind_result($registered_count);
        $attendeeStmt->fetch();
        $attendeeStmt->close();
        
        $remaining_capacity = $event['capacity'] - $registered_count;
        
        $output .= '<tr>
            <td>' . htmlspecialchars($event['name']) . '</td>
            <td>' . htmlspecialchars($event['description']) . '</td>
            <td>' . $event['capacity'] . '</td>
            <td>' . max($remaining_capacity, 0) . '</td>
            <td>';
        
        if ($remaining_capacity > 0) {
            $output .= '<form action="register_event.php" method="POST" class="d-inline-block">
                <input type="hidden" name="event_id" value="' . $event['id'] . '">
                <button type="submit" class="btn btn-success">Register</button>
            </form>';
        } else {
            $output .= '<span class="badge bg-danger">Event Full</span>';
        }

        $output .= '</td></tr>';
    }
} else {
    $output .= '<tr><td colspan="5" class="text-center">No Data Found</td></tr>';
}

$output .= '</tbody></table>';

// Pagination
$totalPages = ceil($totalRecords / $limit);
$output .= '<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">';

for ($i = 1; $i <= $totalPages; $i++) {
    $activeClass = ($i == $page) ? 'active' : '';
    $output .= '<li class="page-item ' . $activeClass . '">
        <a class="page-link" href="#" data-page="' . $i . '">' . $i . '</a>
    </li>';
}

$output .= '</ul></nav>';

echo $output;
?>
