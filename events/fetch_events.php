<?php
include '../includes/db.php';



// if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
//     echo "Forbidden: You are not an admin and do not have access to this page.";
//     exit();
// }

$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$offset = ($page - 1) * $limit;


$sql = "SELECT e.id AS event_id, e.name AS event_name, u.name AS attendee_name
        FROM attendees a
        INNER JOIN events e ON a.event_id = e.id
        INNER JOIN users u ON a.user_id = u.id";


if (!empty($search)) {
    $sql .= " WHERE e.name LIKE ? OR u.name LIKE ?";
}


$sql .= " LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);

// Bind parameters
if (!empty($search)) {
    $searchTerm = "%$search%";
    $stmt->bind_param("ssii", $searchTerm, $searchTerm, $limit, $offset);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

$reports = [];
while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

// Count total records for pagination
$countQuery = "SELECT COUNT(*) AS total FROM attendees a
               INNER JOIN events e ON a.event_id = e.id
               INNER JOIN users u ON a.user_id = u.id";

if (!empty($search)) {
    $countQuery .= " WHERE e.name LIKE ? OR u.name LIKE ?";
    $countStmt = $conn->prepare($countQuery);
    $countStmt->bind_param("ss", $searchTerm, $searchTerm);
} else {
    $countStmt = $conn->prepare($countQuery);
}

$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);
?>

<table class="table table-bordered">
    <thead>
        <tr>
           
            <th>Event Name</th>
            <th>Attendee Name</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($reports)): ?>
            <?php foreach ($reports as $report): ?>
            <tr>
                
                <td><?= htmlspecialchars($report['event_name']) ?></td>
                <td><?= htmlspecialchars($report['attendee_name']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="3" class="text-center">No records found.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Pagination -->
<nav>
    <ul class="pagination">
        <?php if ($page > 1): ?>
            <li class="page-item"><a class="page-link" href="#" data-page="<?= $page - 1 ?>">Previous</a></li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="#" data-page="<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <li class="page-item"><a class="page-link" href="#" data-page="<?= $page + 1 ?>">Next</a></li>
        <?php endif; ?>
    </ul>
</nav>
