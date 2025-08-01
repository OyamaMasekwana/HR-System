<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/Employee.php';


// Get attendance records
$query = "SELECT a.*, e.name 
          FROM Attendance a 
          JOIN Employee e ON a.employeeId = e.employeeId 
          ORDER BY a.attendanceDate DESC";
$attendance = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<h2>Attendance Records</h2>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Employee</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attendance as $record): ?>
            <tr>
                <td><?= htmlspecialchars($record['attendanceDate']) ?></td>
                <td><?= htmlspecialchars($record['name']) ?></td>
                <td>
                    <span class="badge bg-<?= $record['status'] === 'Present' ? 'success' : 'danger' ?>">
                        <?= htmlspecialchars($record['status']) ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>