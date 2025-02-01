<?php
include '../includes/db.php';
include '../includes/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $name, $hashed_password, $role);
    $stmt->fetch();

    if ($id && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_role'] = $role;

        if ($role == 'admin') {
            header('Location: ' . BASE_URL . 'events/admin_dashboard.php');
        } else {
            header('Location: ' . BASE_URL . 'events/dashboard.php');
        }
        exit;
    } else {
        $error = "Invalid email or password.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function validateForm(event) {
            event.preventDefault(); // Prevent form submission
            
            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value.trim();
            let emailError = document.getElementById("emailError");
            let passwordError = document.getElementById("passwordError");
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            let isValid = true;

            // Clear previous errors
            emailError.innerText = "";
            passwordError.innerText = "";

            if (!emailRegex.test(email)) {
                emailError.innerText = "Invalid email format.";
                isValid = false;
            }
            if (password.length < 3) {
                passwordError.innerText = "Password must be at least 3 characters long.";
                isValid = false;
            }

            if (isValid) {
                document.getElementById("loginForm").submit(); // Submit form if valid
            }
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <h2>Login</h2>
    <form id="loginForm" method="POST" action="">
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
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary" onclick="validateForm(event)">Login</button>
         <!-- <div class="mt-3">
            <a href="register.php" class="btn btn-secondary">Go to Register</a>
            <a href="../index.php" class="btn btn-info">Home</a>
         </div> -->
           <!-- Add links to Signup and Home pages -->
        <div class="mt-3">
            <p>Don't have an account? <a href="<?= BASE_URL ?>auth/register.php">Signup here</a>.</p>
            <p>Go back to <a href="<?= BASE_URL ?>index.php">Home</a>.</p>
        </div>
    </form>
</div>
</body>
</html>
