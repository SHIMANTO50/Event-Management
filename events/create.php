<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $capacity = $_POST['capacity'];
    $created_by = $_SESSION['user_id'];

    $stmt = $conn->prepare('INSERT INTO events (name, description, capacity, created_by) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssii', $name, $description, $capacity, $created_by);

    if ($stmt->execute()) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo 'Error: ' . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <div class="content mt-5 container">
        <h2>Create Event</h2>
        <form method="POST" action="" onsubmit="validateForm(event)">
            <div class="mb-3">
                <label for="name" class="form-label">Event Name</label>
                <input type="text" class="form-control" id="name" name="name">
                <div id="nameError" class="error-message"></div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                <div id="descriptionError" class="error-message"></div>
            </div>
            <div class="mb-3">
                <label for="capacity" class="form-label">Capacity</label>
                <input type="number" class="form-control" id="capacity" name="capacity">
                <div id="capacityError" class="error-message"></div>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      
        function validateForm(event) {
            const name = document.getElementById('name').value.trim();
            const description = document.getElementById('description').value.trim();
            const capacity = document.getElementById('capacity').value.trim();

            
            document.getElementById('nameError').innerText = '';
            document.getElementById('descriptionError').innerText = '';
            document.getElementById('capacityError').innerText = '';

            let isValid = true;

            // Validate event name
            if (name.length === 0) {
                document.getElementById('nameError').innerText = "Event Name is required.";
                isValid = false;
            } else if (name.length > 100) {
                document.getElementById('nameError').innerText = "Event Name cannot exceed 100 characters.";
                isValid = false;
            }

            // Validate description
            if (description.length === 0) {
                document.getElementById('descriptionError').innerText = "Description is required.";
                isValid = false;
            } else if (description.length > 500) {
                document.getElementById('descriptionError').innerText = "Description cannot exceed 500 characters.";
                isValid = false;
            }

            // Validate capacity
            if (capacity.length === 0) {
                document.getElementById('capacityError').innerText = "Capacity is required.";
                isValid = false;
            } else if (isNaN(capacity) || capacity <= 0) {
                document.getElementById('capacityError').innerText = "Capacity must be a positive number.";
                isValid = false;
            }

           
            if (!isValid) {
                event.preventDefault();
            }
        }
    </script>
</body>

</html>
