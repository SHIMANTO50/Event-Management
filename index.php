<?php
session_start();

// Redirect logged-in users to the dashboard
if (isset($_SESSION['user_id'])) {
    $loggedIn = true;
} else {
    $loggedIn = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Welcome to Event Management System</h1>
        <div class="d-flex justify-content-center mt-4">
            <?php if ($loggedIn): ?>
                <a href="auth/logout.php" class="btn btn-danger mx-2">Logout</a>
            <?php else: ?>
                <a href="auth/login.php" class="btn btn-primary mx-2">Login</a>
                <a href="auth/register.php" class="btn btn-success mx-2">Register</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
