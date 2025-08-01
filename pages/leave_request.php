<?php
session_start();

// 1. Access Restriction: Only admin role allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<h3 style='color: red;'>Access denied. You must be an HR Manager (admin) to view this page.</h3>";
    exit();
}

// 2. Database connection
$host = 'localhost';
$user = 'root';
$pass = 'OM1710004'; // update with your actual password
$db = 'company_hr';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 3. Handle new leave request submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_request'])) {
    $employeeId = $_POST['employeeId'];
    $leaveTypeId = $_POST['leaveTypeId'];
    $leaveDate = $_POST['leaveDate'];
    $endDate = $_POST['endDate'];
    $reason = $_POST['reason'];
    $status = $_POST['status'];

    // Calculate days requested (+1 because inclusive)
    $daysRequested = (strtotime($endDate) - strtotime($leaveDate)) / (60 * 60 * 24) + 1;

    $stmt = $conn->prepare("INSERT INTO LeaveRequest (employeeId, leaveTypeId, leaveDate, endDate, reason, daysRequested, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssis", $employeeId, $leaveTypeId, $leaveDate, $endDate, $reason, $daysRequested, $status);
    $stmt->execute();
    $stmt->close();

    $message = "Leave request submitted successfully.";
}

// 4. Handle status update for existing requests
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $leaveId = $_POST['leaveId'];
    $newStatus = $_POST['status'];

    $stmt = $conn->prepare("UPDATE LeaveRequest SET status = ? WHERE leaveId = ?");
    $stmt->bind_param("si", $newStatus, $leaveId);
    $stmt->execute();
    $stmt->close();

    $message = "Leave request status updated.";
}

// 5. Fetch dropdown data for employees and leave types
$employees = $conn->query("SELECT employeeId, name FROM Employee ORDER BY name");
$leaveTypes = $conn->query("SELECT leaveTypeId, name FROM LeaveType ORDER BY name");

// 6. Fetch all leave requests with employee and leave type names
$leaveRequests = $conn->query("
    SELECT lr.leaveId, e.name AS employeeName, lt.name AS leaveTypeName, lr.leaveDate, lr.endDate, lr.reason, lr.daysRequested, lr.status 
    FROM LeaveRequest lr
    JOIN Employee e ON lr.employeeId = e.employeeId
    JOIN LeaveType lt ON lr.leaveTypeId = lt.leaveTypeId
    ORDER BY lr.leaveDate DESC
");
?>

<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>HR Leave Requests Management</title>
<style>
    /* body {
        font-family: Arial, sans-serif;
        max-width: 900px;
        margin: 20px auto;
        padding: 10px;
    } */
    h1, h2 {
        text-align: center;
    }
    form, table {
        width: 100%;
        margin-bottom: 30px;
    }
    label {
        font-weight: bold;
        display: block;
        margin-top: 10px;
    }
    input[type=date], select, textarea {
        width: 100%;
        padding: 7px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    textarea {
        resize: vertical;
        height: 80px;
    }
    button {
        margin-top: 15px;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
        font-size: 16px;
    }
    button:hover {
        background-color: #45a049;
    }
    table {
        border-collapse: collapse;
    }
    table, th, td {
        border: 1px solid #aaa;
    }
    th, td {
        padding: 8px;
        text-align: left;
    }
    select.status-dropdown {
        padding: 5px 8px;
        border-radius: 4px;
        border: 1px solid #aaa;
        cursor: pointer;
        font-weight: bold;
    }
    select.status-dropdown.pending {
        background-color: orange;
        color: white;
    }
    select.status-dropdown.approved {
        background-color: green;
        color: white;
    }
    select.status-dropdown.denied {
        background-color: red;
        color: white;
    }
    .message {
        text-align: center;
        font-weight: bold;
        margin-bottom: 20px;
        color: green;
    }
</style>
<script>
    function updateDropdownColor(sel) {
        sel.classList.remove('pending', 'approved', 'denied');
        const val = sel.value.toLowerCase();
        if(val === 'pending' || val === 'approved' || val === 'denied') {
            sel.classList.add(val);
        }
    }
    window.onload = function() {
        document.querySelectorAll('select.status-dropdown').forEach(sel => {
            updateDropdownColor(sel);
            sel.addEventListener('change', function() {
                updateDropdownColor(this);
                this.form.submit();
            });
        });
    }
</script>
</head>
<body>
<h1>HR Leave Requests Management</h1>

<?php if (!empty($message)): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<!-- <h2>Submit New Leave Request</h2> -->
<form method="POST" action="">
    <label for="employeeId">Employee:</label>
    <select name="employeeId" id="employeeId" required>
        <option value="">-- Select Employee --</option>
        <?php while ($emp = $employees->fetch_assoc()): ?>
            <option value="<?= $emp['employeeId'] ?>"><?= htmlspecialchars($emp['name']) ?></option>
        <?php endwhile; ?>
    </select>

    <label for="leaveTypeId">Leave Type:</label>
    <select name="leaveTypeId" id="leaveTypeId" required>
        <option value="">-- Select Leave Type --</option>
        <?php while ($lt = $leaveTypes->fetch_assoc()): ?>
            <option value="<?= $lt['leaveTypeId'] ?>"><?= htmlspecialchars($lt['name']) ?></option>
        <?php endwhile; ?>
    </select>

    <label for="leaveDate">Start Date:</label>
    <input type="date" name="leaveDate" id="leaveDate" required>

    <label for="endDate">End Date:</label>
    <input type="date" name="endDate" id="endDate" required>

    <label for="reason">Reason:</label>
    <textarea name="reason" id="reason" required></textarea>

    <label for="status">Status:</label>
    <select name="status" id="status" required>
        <option value="Pending" style="background-color: orange; color: white;">Pending</option>
        <option value="Approved" style="background-color: green; color: white;">Approved</option>
        <option value="Denied" style="background-color: red; color: white;">Denied</option>
    </select>

    <button type="submit" name="submit_request">Submit Leave Request</button>
</form>

<h2>Existing Leave Requests</h2>
<table>
    <thead>
        <tr>
            <th>Employee</th>
            <th>Leave Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Days</th>
            <th>Reason</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($lr = $leaveRequests->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($lr['employeeName']) ?></td>
            <td><?= htmlspecialchars($lr['leaveTypeName']) ?></td>
            <td><?= htmlspecialchars($lr['leaveDate']) ?></td>
            <td><?= htmlspecialchars($lr['endDate']) ?></td>
            <td><?= htmlspecialchars($lr['daysRequested']) ?></td>
            <td><?= nl2br(htmlspecialchars($lr['reason'])) ?></td>
            <td>
                <form method="POST" style="margin:0;">
                    <input type="hidden" name="leaveId" value="<?= $lr['leaveId'] ?>">
                    <select name="status" class="status-dropdown" required>
                        <option value="Pending" <?= $lr['status'] === 'Pending' ? 'selected' : '' ?> >Pending</option>
                        <option value="Approved" <?= $lr['status'] === 'Approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="Denied" <?= $lr['status'] === 'Denied' ? 'selected' : '' ?>>Denied</option>
                    </select>
                    <input type="hidden" name="update_status" value="1">
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>


<?php include '../includes/footer.php'; ?>