<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/Employee.php';


// Get search input from GET request
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$employee = new Employee($conn);
$employees = !empty($search) 
    ? $employee->searchEmployees($search) 
    : $employee->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee'])) {
    $data = [
        'name' => $_POST['name'],
        'position' => $_POST['position'],
        'department' => $_POST['department'],
        'salary' => $_POST['salary'],
        'contact' => $_POST['contact']
    ];
    
    if ($employee->create($data)) {
        header('Location: employees.php');
        exit;
    }
}

$departments = $conn->query("SELECT departmentName FROM Department")->fetch_all(MYSQLI_ASSOC);
?>

<?php include '../includes/header.php'; ?>


<link rel="stylesheet" href="../assets/css/style.css">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Employee Directory</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
        <i class="bi bi-plus-lg"></i> Add Employee
    </button>
</div>
<?php
$placeholder = "Search by name, position or department";
include '../includes/search_bar.php'; 
?>

<div class="row">
    <?php if (empty($employees)): ?>
        <div class="col-12">
            <div class="alert alert-info">No employees found.</div>
        </div>
    <?php else: ?>
        <?php foreach ($employees as $emp): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($emp['name']) ?></h5>
                    <p class="card-text">
                        <strong>Position:</strong> <?= htmlspecialchars($emp['position']) ?><br>
                        <strong>Department:</strong> <?= htmlspecialchars($emp['department']) ?><br>
                        <strong>Salary:</strong> R<?= number_format($emp['salary'], 2) ?><br>
                        <strong>Email:</strong> <?= htmlspecialchars($emp['contact']) ?>
                    </p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="edit_employee.php?id=<?= $emp['employeeId'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete_employee.php?id=<?= $emp['employeeId'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Position</label>
                        <input type="text" name="position" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select name="department" class="form-select" required>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= htmlspecialchars($dept['departmentName']) ?>">
                                    <?= htmlspecialchars($dept['departmentName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Salary</label>
                        <input type="number" step="0.01" name="salary" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_employee" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php include '../includes/footer.php'; ?>