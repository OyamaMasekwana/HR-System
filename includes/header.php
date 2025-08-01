<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    echo '<!-- Session started -->';
}
 echo '<!-- Session status: ' . session_status() . ' -->';
?>
<!DOCTYPE html>
<html lang="en">
<?php
// After session_start()
if (function_exists('isLoggedIn') && isLoggedIn()) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        include 'admin_navigation.php';
    } else {
        include 'staff_navigation.php';
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>
    <?php if (isset($_SESSION['username'])):
        echo '<!-- User is logged in as: ' . htmlspecialchars($_SESSION['username']) . ' -->';
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Modern Tech Solutions</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="employees.php">Employees</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="attendance.php">Attendance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="payroll.php">Payroll</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="performance_review.php">Performance Review</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="leave_request.php">Leave Requests</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    <div class="container mt-4">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>