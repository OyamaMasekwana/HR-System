<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Employees</h5>
                <p class="card-text"><?= $employeeCount ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Monthly Payroll (R)</h5>
                <p class="card-text">R <?= number_format($totalSalary) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Avg Salary (R)</h5>
                <p class="card-text">R <?= number_format($avgSalary) ?></p>
            </div>
        </div>
    </div>
</div>






