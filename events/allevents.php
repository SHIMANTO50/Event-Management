<?php
session_start();
include '../includes/db.php'; 


$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .error-message {
            color: red;
            font-size: 0.875rem;
        }

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
    <div class="container mt-5 content">
        <h1>Available Events</h1>

        <!-- Search Form -->
        <div class="mb-3">
            <div class="input-group">
                <input type="text" id="search" class="form-control"
                    placeholder="Search by event name or description">
            </div>
        </div>

        <!-- Event Table -->
        <div id="eventTable">
            <!-- Data will be loaded dynamically via AJAX -->
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            function fetchEvents(page = 1, query = '') {
                $.ajax({
                    url: "search.php",
                    method: "GET",
                    data: {
                        search: query,
                        page: page
                    },
                    success: function(data) {
                        $("#eventTable").html(data);
                    }
                });
            }

           
            fetchEvents();

            
            $("#search").on("keyup", function() {
                let query = $(this).val();
                fetchEvents(1, query); 
            });

            // Pagination event delegation
            $(document).on("click", ".pagination .page-link", function(event) {
                event.preventDefault();
                let page = $(this).data("page"); 
                let query = $("#search").val(); 
                fetchEvents(page, query);
            });
        });
    </script>
</body>

</html>
