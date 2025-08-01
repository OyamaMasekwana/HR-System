<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/Employee.php'; // Add this line
require_once('../lib/tcpdf/tcpdf.php');




// Initialize Employee class
$employee = new Employee($conn);

// Get all employees for payroll listing
$employees = $employee->getAll(); // This populates the $employees variable

// Payslip generation
if (isset($_GET['generate_payslip']) && isset($_GET['id'])) {
    $employeeId = $_GET['id'];
    
    // Verify employee exists
    $empData = $employee->getById($employeeId);
    if (!$empData) {
        header('Location: payroll.php');
        exit;
    }

    // Generate PDF (requires TCPDF)
    require_once __DIR__ . '/../lib/tcpdf/tcpdf.php';

    // Clear output buffer to prevent header issues
    if (ob_get_length()) {
        ob_end_clean();
    }
    
    $pdf = new TCPDF();
    $pdf->AddPage();
    
  // PDF Content
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Payslip for ' . $empData['name'], 0, 1);

// Add more details
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Position: ' . $empData['position'], 0, 1);
$pdf->Cell(0, 10, 'Gross Salary: R' . number_format($empData['salary'], 2), 0, 1);

// Deductions (example logic)
$grossSalary = $empData['salary'];
$tax = $grossSalary * 0.15;       // 15% tax
$pension = $grossSalary * 0.05;   // 5% pension
$uif = $grossSalary * 0.01;       // 1% UIF
$totalDeductions = $tax + $pension + $uif;
$netPay = $grossSalary - $totalDeductions;

// Show deductions
$pdf->Ln(5); // Line break
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Deductions:', 0, 1);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Tax (15%): R' . number_format($tax, 2), 0, 1);
$pdf->Cell(0, 10, 'Pension (5%): R' . number_format($pension, 2), 0, 1);
$pdf->Cell(0, 10, 'UIF (1%): R' . number_format($uif, 2), 0, 1);
$pdf->Cell(0, 10, 'Total Deductions: R' . number_format($totalDeductions, 2), 0, 1);

// Net Pay
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Net Pay: R' . number_format($netPay, 2), 0, 1);

    $pdf->Output('payslip_' . $employeeId . '.pdf', 'D');
    exit;
}
?>

<?php include '../includes/header.php'; ?>

<h2>Payroll Management</h2>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Position</th>
                <th>Department</th>
                <th>Salary</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($employees)): ?>
                <?php foreach ($employees as $emp): ?>
                <tr>
                    <td><?= htmlspecialchars($emp['name']) ?></td>
                    <td><?= htmlspecialchars($emp['position']) ?></td>
                    <td><?= htmlspecialchars($emp['department'] ?? 'N/A') ?></td>
                    <td>R <?= number_format($emp['salary'], 2) ?></td>
                    <td>
                        <a href="payroll.php?generate_payslip=1&id=<?= $emp['employeeId'] ?>" 
                           class="btn btn-sm btn-success">
                            Generate Payslip
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No employees found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>