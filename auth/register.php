<?php
include '../includes/db.php';
include '../includes/config.php';
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user'; // Default role for new registrations

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        // Fetch the inserted user data
        $user_id = $stmt->insert_id;

        // Set session data to log the user in
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_role'] = $role;

        if ($role == 'admin') {
            header('Location: ' . BASE_URL . 'events/admin_dashboard.php');
        } else {
            header('Location: ' . BASE_URL . 'events/dashboard.php');
        }
        exit;
      
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function validateForm(event) {
            event.preventDefault(); // Prevent form submission

            let name = document.getElementById("name").value.trim();
            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value.trim();
            let nameError = document.getElementById("nameError");
            let emailError = document.getElementById("emailError");
            let passwordError = document.getElementById("passwordError");
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            let isValid = true;

            // Clear previous errors
            nameError.innerText = "";
            emailError.innerText = "";
            passwordError.innerText = "";

            // Name validation
            if (name.length < 3) {
                nameError.innerText = "Name must be at least 3 characters long.";
                isValid = false;
            }

            // Email validation
            if (!emailRegex.test(email)) {
                emailError.innerText = "Invalid email format.";
                isValid = false;
            }

            // Password validation
            if (password.length < 3) {
                passwordError.innerText = "Password must be at least 3 characters long.";
                isValid = false;
            }

            if (isValid) {
                document.getElementById("registerForm").submit(); // Submit form if valid
            }
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <h2>Register</h2>
    <form id="registerForm" method="POST" action="">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name">
            <small id="nameError" class="text-danger"></small>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email">
            <small id="emailError" class="text-danger"></small>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
            <small id="passwordError" class="text-danger"></small>
        </div>
        <button type="submit" class="btn btn-primary" onclick="validateForm(event)">Register</button>
         <!-- Add links to Login and Home pages -->
        <div class="mt-3">
            <p>Already have an account? <a href="<?= BASE_URL ?>auth/login.php">Login here</a>.</p>
            <p>Go back to <a href="<?= BASE_URL ?>index.php">Home</a>.</p>
        </div>
    </form>
</div>
</body>
</html>
