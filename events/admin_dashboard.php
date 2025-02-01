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
        <h1 class="mb-4">Event Reports</h1>
        
        <!-- Search Input -->
        <input type="text" id="search" class="form-control mb-3" placeholder="Search by event or attendee name...">

        <!-- Download Report -->
        <a href="download_reports.php" class="btn btn-primary mt-2 mb-2">Download CSV</a>

        <!-- Event Table -->
        <div id="eventTable">
            <?php include 'fetch_events.php'; ?>
        </div>

        
    </div>

    <script>
        $(document).ready(function() {
            function fetchEvents(page = 1, search = '') {
                $.ajax({
                    url: "fetch_events.php",
                    type: "GET",
                    data: { page: page, search: search },
                    success: function(data) {
                        $("#eventTable").html(data);
                    }
                });
            }

            // Search feature
            $("#search").on("keyup", function() {
                let search = $(this).val();
                fetchEvents(1, search);
            });

            // Handle pagination click
            $(document).on("click", ".pagination a", function(e) {
                e.preventDefault();
                let page = $(this).attr("data-page");
                let search = $("#search").val();
                fetchEvents(page, search);
            });

            fetchEvents();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
