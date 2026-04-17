<?php 
include('db_connect.php'); 
// Note: header.php ko index.php handle kar raha hai, 
// lekin agar aap direct dashboard.php open karte hain to ye line zaroori hai.
// Agar double header aa raha ho to niche wali line ko delete kar dein.
include('header.php'); 

// Data Counts
$b = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM buses"))['t'] ?? 0;
$r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM routes"))['t'] ?? 0;
$s = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM students"))['t'] ?? 0;
$d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM drivers"))['t'] ?? 0;
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
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 15px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark m-0"><i class="fas fa-chart-bar me-2 text-primary"></i> Fuel vs Maintenance Analysis</h5>
                    <span class="badge bg-light text-dark p-2">Monthly Data 2026</span>
                </div>
                <div style="height: 350px;">
                    <canvas id="fuelChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const ctx = document.getElementById('fuelChart').getContext('2d');
    
    // Static Sample Data (In future we can connect this with DB)
    const fuelData = [15000, 22000, 18000, 28000, 21000, 32000];
    const maintenanceData = [5000, 8500, 4200, 11000, 6000, 9500];
    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Fuel Consumption (PKR)',
                    data: fuelData,
                    backgroundColor: '#2196f3',
                    borderRadius: 6,
                    barThickness: 25
                },
                {
                    label: 'Maintenance Cost (PKR)',
                    data: maintenanceData,
                    backgroundColor: '#f44336',
                    borderRadius: 6,
                    barThickness: 25
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            }
        }
    });
});
</script>