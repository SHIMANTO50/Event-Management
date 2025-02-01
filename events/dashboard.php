<?php
include '../includes/db.php';
include '../includes/config.php';
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'auth/login.php');
    exit();
}


if ($_SESSION['user_role'] === 'admin') {
    echo 'Forbidden: You are an admin and do not have access to this page.';
    exit();
}



$user_id = $_SESSION['user_id'];


$itemsPerPage = 5; 
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';


$offset = ($page - 1) * $itemsPerPage;

// Fetch events created by the logged-in user with filtering and pagination
$query = 'SELECT * FROM events WHERE created_by = ? AND (name LIKE ? OR description LIKE ?) ORDER BY id DESC LIMIT ? OFFSET ?';
$stmt = $conn->prepare($query);
$searchParam = '%' . $search . '%';
$stmt->bind_param('issii', $user_id, $searchParam, $searchParam, $itemsPerPage, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Count total events for pagination
$countQuery = 'SELECT COUNT(*) AS total FROM events WHERE created_by = ? AND (name LIKE ? OR description LIKE ?)';
$countStmt = $conn->prepare($countQuery);
$countStmt->bind_param('iss', $user_id, $searchParam, $searchParam);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalItems = $countResult->fetch_assoc()['total'];

// Calculate total pages
$totalPages = ceil($totalItems / $itemsPerPage);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Event Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
        }

        .sidebar {
            width: 250px;
            background-color: #f8f9fa;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        @media (max-width: 992px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
                padding: 10px;
            }

            .content {
                margin-left: 0;
                width: 100%;
                padding: 10px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .content {
                margin-left: 0;
                padding: 10px;
            }
        }

        table {
            table-layout: fixed;
            word-wrap: break-word;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/sidebar.php'; ?>


    <div class="content">
        <h2>Your Events</h2>
        <a href="create.php" class="btn btn-success mb-3">Create New Event</a>

        <input type="text" id="search-box" class="form-control mb-3" placeholder="Search events..."
            value="<?= htmlspecialchars($search) ?>">

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                    <?php
                    $serialNumber = ($page - 1) * $itemsPerPage + 1; // Start serial number based on pagination
                    ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $serialNumber++ ?></td> <!-- Sequential numbering -->
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td><?= $row['capacity'] ?></td>
                        <td>
                            <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-danger">Data is not found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>


            </table>
        </div>

        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const searchBox = document.getElementById('search-box');
        const tableBody = document.querySelector('table tbody');
        const pagination = document.querySelector('.pagination');

        searchBox.addEventListener('input', function() {
            const searchValue = this.value;

            fetch(`dashboard.php?search=${encodeURIComponent(searchValue)}`)
                .then(response => response.text())
                .then(data => {
                    const parser = new DOMParser();
                    const htmlDoc = parser.parseFromString(data, 'text/html');
                    const newTableBody = htmlDoc.querySelector('table tbody').innerHTML;
                    const newPagination = htmlDoc.querySelector('.pagination') ? htmlDoc.querySelector(
                        '.pagination').innerHTML : '';

                    tableBody.innerHTML = newTableBody;
                    pagination.innerHTML = newPagination || '';
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>
