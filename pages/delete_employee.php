<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/Employee.php';


if (!isset($_GET['id'])) {
    header('Location: employees.php');
    exit;
}

$employee = new Employee($conn);
$id = $_GET['id'];

// Verify employee exists
$empData = $employee->getById($id);
if (!$empData) {
    header('Location: employees.php');
    exit;
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee->delete($id);
    header('Location: employees.php');
    exit;
}
?>

<?php include '../includes/header.php'; ?>

<h2>Delete Employee</h2>

<div class="alert alert-warning">
    Are you sure you want to delete <strong><?= htmlspecialchars($empData['name']) ?></strong>?
</div>

<form method="POST">
    <button type="submit" class="btn btn-danger">Confirm Delete</button>
    <a href="employees.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include '../includes/footer.php'; ?>