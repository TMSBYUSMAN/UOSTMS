<?php 
include('db_connect.php'); 
// Stats fetching logic
$buses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM buses"))['t'] ?? 0;
$routes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM routes"))['t'] ?? 0;
$students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM students"))['t'] ?? 0;
$drivers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM drivers"))['t'] ?? 0;
?>

<div class="container-fluid p-0">
    <div class="mb-4">
        <h2 class="fw-bold text-dark">Management Dashboard</h2>
        <p class="text-muted small">Transport System Analytics Overview</p>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; border-left: 5px solid #2196f3;">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-bus fa-2x text-primary mb-3 opacity-50"></i>
                    <h2 class="fw-bold mb-0"><?php echo $buses; ?></h2>
                    <span class="text-muted small fw-bold text-uppercase">Buses</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; border-left: 5px solid #4caf50;">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-route fa-2x text-success mb-3 opacity-50"></i>
                    <h2 class="fw-bold mb-0"><?php echo $routes; ?></h2>
                    <span class="text-muted small fw-bold text-uppercase">Routes</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; border-left: 5px solid #ff9800;">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-user-graduate fa-2x text-warning mb-3 opacity-50"></i>
                    <h2 class="fw-bold mb-0"><?php echo $students; ?></h2>
                    <span class="text-muted small fw-bold text-uppercase">Students</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; border-left: 5px solid #f44336;">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-user-tie fa-2x text-danger mb-3 opacity-50"></i>
                    <h2 class="fw-bold mb-0"><?php echo $drivers; ?></h2>
                    <span class="text-muted small fw-bold text-uppercase">Drivers</span>
                </div>
            </div>
        </div>
    </div>
</div>