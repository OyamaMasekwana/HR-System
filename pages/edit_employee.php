<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/Employee.php';



$employee = new Employee($conn);

// Check if employee ID is provided
if (!isset($_GET['id'])) {
    header('Location: employees.php');
    exit;
}

$id = $_GET['id'];
$empData = $employee->getById($id);

// If employee doesn't exist, redirect back
if (!$empData) {
    header('Location: employees.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'position' => $_POST['position'],
        'department' => $_POST['department'],
        'salary' => $_POST['salary'],
        'contact' => $_POST['contact']
    ];
    
    if ($employee->update($id, $data)) {
        header('Location: employees.php');
        exit;
    }
}

// Get departments for dropdown
$departments = $conn->query("SELECT departmentName FROM Department")->fetch_all(MYSQLI_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<h2>Edit Employee</h2>

<form method="POST">
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($empData['name']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Position</label>
        <input type="text" name="position" class="form-control" value="<?= htmlspecialchars($empData['position']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Department</label>
        <select name="department" class="form-select" required>
            <?php foreach ($departments as $dept): ?>
                <option value="<?= htmlspecialchars($dept['departmentName']) ?>" 
                    <?= $dept['departmentName'] === $empData['department'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($dept['departmentName']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Salary</label>
        <input type="number" step="0.01" name="salary" class="form-control" value="<?= htmlspecialchars($empData['salary']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Contact Email</label>
        <input type="email" name="contact" class="form-control" value="<?= htmlspecialchars($empData['contact']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="employees.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include '../includes/footer.php'; ?>