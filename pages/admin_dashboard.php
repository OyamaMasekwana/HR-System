<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';

$Employee = new Employee($conn);
$employeeCount = $conn->query("SELECT COUNT(*) FROM Employee")->fetch_row()[0];
$totalSalary = $conn->query("SELECT SUM(salary) FROM Employee")->fetch_row()[0];
$avgSalary = $employeeCount > 0 ? $totalSalary / $employeeCount : 0;
$deptStats = $Employee->getDepartmentStats();
?>
<?php
include '../includes/header.php'; 
// session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
// Admin-specific content
?>
<h2>Dashboard Overview</h2>
    <!-- Say Welcome username -->
    <?php if (isset($_SESSION['username'])): ?>
        <div class="alert alert-info">
            Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            You are not logged in.
        </div>

    <?php endif; ?>
<?php include '../includes/summary_cards.php'; ?>
<?php include '../includes/charts.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const salaryCtx = document.getElementById('salaryChart').getContext('2d');
    new Chart(salaryCtx, {
        type: 'bar',
        data: {
            labels: ['Total Salary', 'Average Salary'],
            datasets: [{
                label: 'Salary Data',
                data: [<?= $totalSalary ?>, <?= $avgSalary ?>],
                backgroundColor: ['rgba(54, 162, 235, 0.5)', 'purple']
            }]
        }
    });
    const deptCtx = document.getElementById('departmentChart').getContext('2d');
    new Chart(deptCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_column($deptStats, 'department')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($deptStats, 'count')) ?>,
                backgroundColor: [
                    'blue',
                    'rgba(54, 162, 235, 0.5)',
                    'violet',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)'
                ]
            }]
        }
    });
});
</script>
<?php include '../includes/footer.php'; ?>






