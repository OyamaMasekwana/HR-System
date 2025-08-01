<?php if (isLoggedIn()): ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Modern Tech Solutions</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'admin_dashboard.php' ? 'active' : '' ?>" 
                       href="admin_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'employees.php' ? 'active' : '' ?>" 
                       href="employees.php">Employees</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'payroll.php' ? 'active' : '' ?>" 
                       href="payroll.php">Payroll</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'attendance.php' ? 'active' : '' ?>" 
                       href="attendance.php">Attendance</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'leave_request.php' ? 'active' : '' ?>" 
                       href="leave_request.php">Leave Requests</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'performance_review.php' ? 'active' : '' ?>" 
                       href="performance_review.php">Performance Review</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'performance.php' ? 'active' : '' ?>" 
                       href="performance.php">Performance</a>
                </li>
            </ul>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</nav>
<?php endif; ?>