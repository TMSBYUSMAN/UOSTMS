<?php 
include('db_connect.php'); 

// Agar form submit ho to data save karein
if(isset($_POST['add_expense'])){
    $month = $_POST['month_name'];
    $fuel = $_POST['fuel_amount'];
    $maint = $_POST['maintenance_amount'];
    
    mysqli_query($conn, "INSERT INTO fuel_expenses (month_name, fuel_amount, maintenance_amount) VALUES ('$month', '$fuel', '$maint')");
    echo "<script>alert('Record Added Successfully');</script>";
}
?>

<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold text-dark">Fuel & Maintenance Management</h2>
        <p class="text-muted small">Add and track monthly expenses</p>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 15px;">
                <h5 class="fw-bold mb-3">Add New Record</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Month Name</label>
                        <select name="month_name" class="form-control" required>
                            <option value="Jan">January</option>
                            <option value="Feb">February</option>
                            <option value="Mar">March</option>
                            <option value="Apr">April</option>
                            <option value="May">May</option>
                            <option value="Jun">June</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fuel Amount (PKR)</label>
                        <input type="number" name="fuel_amount" class="form-control" placeholder="e.g. 15000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Maintenance Cost (PKR)</label>
                        <input type="number" name="maintenance_amount" class="form-control" placeholder="e.g. 5000" required>
                    </div>
                    <button type="submit" name="add_expense" class="btn btn-primary w-100" style="border-radius: 8px;">Save Expense</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 15px;">
                <h5 class="fw-bold mb-3">Expense History</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Month</th>
                                <th>Fuel (PKR)</th>
                                <th>Maintenance (PKR)</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $res = mysqli_query($conn, "SELECT * FROM fuel_expenses ORDER BY id DESC");
                            while($row = mysqli_fetch_assoc($res)){
                                echo "<tr>
                                    <td>{$row['month_name']}</td>
                                    <td class='text-primary fw-bold'>".number_format($row['fuel_amount'])."</td>
                                    <td class='text-danger fw-bold'>".number_format($row['maintenance_amount'])."</td>
                                    <td class='fw-bold'>".number_format($row['fuel_amount'] + $row['maintenance_amount'])."</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>