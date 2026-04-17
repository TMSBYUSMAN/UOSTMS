<?php 
include('db_connect.php'); 
include('header.php'); 
?>

<div class="container-fluid px-4 mt-4">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">System Reports</h3>
        <p class="text-muted small">Generated analytics and data summaries</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 15px; border-top: 5px solid #2196f3;">
                <h5 class="fw-bold"><i class="fas fa-file-pdf text-danger me-2"></i>Download Student List</h5>
                <p class="text-muted">Export all registered students in PDF format.</p>
                <button class="btn btn-outline-primary fw-bold">Generate PDF</button>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 15px; border-top: 5px solid #4caf50;">
                <h5 class="fw-bold"><i class="fas fa-file-excel text-success me-2"></i>Fleet Performance</h5>
                <p class="text-muted">Export bus and route statistics to Excel.</p>
                <button class="btn btn-outline-success fw-bold">Generate Excel</button>
            </div>
        </div>
    </div>
</div>
</div></body></html>