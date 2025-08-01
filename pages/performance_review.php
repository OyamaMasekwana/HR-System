<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../classes/Employee.php';

// Instantiate Employee class and get all employee data
$Employee = new Employee($conn);
$employees = $Employee->getAll();
// Function to generate random review scores
function randomScore() {
    return rand(60, 100);
}
?>

<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Performance Review</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .performance-review {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            background: #F7FAFF;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        table {
            background: white;
        }
        th, td {
            text-align: center;
            vertical-align: middle;
        }
        thead th {
            background-color: #CFE2FF !important;
        }
        tbody tr:hover {
            background-color: #E8F0FE;
        }
    </style>
</head>
<body>
<div class="performance-review">
    <h2 class="mb-4">Employee Performance Review</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Work Quality</th>
                    <th>Communication</th>
                    <th>Teamwork</th>
                    <th>Attendance</th>
                    <th>Overall Score</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $index => $emp):
                    $workQuality = randomScore();
                    $communication = randomScore();
                    $teamwork = randomScore();
                    $attendance = randomScore();
                    $overall = round(($workQuality + $communication + $teamwork + $attendance) / 4);
                ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($emp['name']) ?></td>
                        <td><?= htmlspecialchars($emp['position']) ?></td>
                        <td><?= htmlspecialchars($emp['department']) ?></td>
                        <td><?= $workQuality ?></td>
                        <td><?= $communication ?></td>
                        <td><?= $teamwork ?></td>
                        <td><?= $attendance ?></td>
                        <td><strong><?= $overall ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<?php include '../includes/footer.php'; ?>