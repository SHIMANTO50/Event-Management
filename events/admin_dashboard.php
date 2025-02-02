<?php
session_start();
include '../includes/db.php';
include '../includes/config.php';
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="sidebar">
        <h4>Menu</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../auth/logout.php">Logout</a>
            </li>
        </ul>
    </div>

    <div class="content">
        <h1 class="mb-3">Available Events And Report Download</h1>
        <!-- Error Message Handling -->
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger" id="error-message">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']); // Clear the message after displaying
        }
        ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Attendees</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = 'SELECT * FROM events ORDER BY created_at DESC'; 
                $result = mysqli_query($conn, $query);
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['description']}</td>";
                
                    // Count attendees
                    $event_id = $row['id'];
                    $attendee_query = "SELECT COUNT(*) as total FROM attendees WHERE event_id = $event_id";
                    $attendee_result = mysqli_query($conn, $attendee_query);
                    $attendee_data = mysqli_fetch_assoc($attendee_result);
                    echo "<td>{$attendee_data['total']} Attendees</td>";
                
                    // Download button for each event
                    echo "<td><a href='download_event_attendees.php?event_id={$event_id}' class='btn btn-success btn-sm'>Download Report</a></td>";
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>

    </div>

    <script>
        $(document).ready(function() {
            // Automatically hide the error message after 5 seconds
            setTimeout(function() {
                $('#error-message').fadeOut();
            }, 1000); // Adjust the time as needed (5000ms = 5 seconds)
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
