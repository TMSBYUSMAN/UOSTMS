<?php 
include('db_connect.php'); 
include('header.php'); 

// 1. Data Counts for Top Boxes
$b = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM buses"))['t'] ?? 0;
$r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM routes"))['t'] ?? 0;
$s = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM students"))['t'] ?? 0;
$d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM drivers"))['t'] ?? 0;

// 2. Fetch Real-time Fuel Data from Database for the Chart
$months = [];
$fuel = [];
$maintenance = [];

$chart_query = mysqli_query($conn, "SELECT * FROM fuel_expenses ORDER BY id ASC LIMIT 6");
while($row = mysqli_fetch_assoc($chart_query)) {
    $months[] = $row['month_name'];
    $fuel[] = $row['fuel_amount'];
    $maintenance[] = $row['maintenance_amount'];
}
?>

<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold text-dark">Transport Dashboard</h2>
        <p class="text-muted small">Management Portal Overview</p>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="p-3 shadow-sm border-0 text-white" style="background: #2196f3; border-radius: 12px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div><h2 class="fw-bold mb-0"><?php echo $b; ?></h2><small class="text-uppercase fw-bold">Buses</small></div>
                    <i class="fas fa-bus fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 shadow-sm border-0 text-white" style="background: #4caf50; border-radius: 12px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div><h2 class="fw-bold mb-0"><?php echo $r; ?></h2><small class="text-uppercase fw-bold">Routes</small></div>
                    <i class="fas fa-route fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 shadow-sm border-0 text-white" style="background: #ff9800; border-radius: 12px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div><h2 class="fw-bold mb-0"><?php echo $s; ?></h2><small class="text-uppercase fw-bold">Students</small></div>
                    <i class="fas fa-user-graduate fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 shadow-sm border-0 text-white" style="background: #f44336; border-radius: 12px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div><h2 class="fw-bold mb-0"><?php echo $d; ?></h2><small class="text-uppercase fw-bold">Drivers</small></div>
                    <i class="fas fa-user-tie fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: