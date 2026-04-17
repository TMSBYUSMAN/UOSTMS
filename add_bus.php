<?php 
include('db_connect.php'); 
include('header.php'); 

// 1. DELETE LOGIC
if(isset($_GET['del_bus'])){
    $id = $_GET['del_bus'];
    mysqli_query($conn, "DELETE FROM buses WHERE id = $id");
    echo "<script>window.location.href='add_bus.php';</script>";
}

// 2. SAVE LOGIC
if(isset($_POST['save_bus'])){
    $bus_no = mysqli_real_escape_string($conn, $_POST['bus_no']);
    $driver = mysqli_real_escape_string($conn, $_POST['driver_name']);
    $helper = mysqli_real_escape_string($conn, $_POST['helper_name']);
    
    $query = "INSERT INTO buses (bus_no, driver_name, helper_name, status) 
              VALUES ('$bus_no', '$driver', '$helper', 'Active')";
    
    if(mysqli_query($conn, $query)){
        echo "<script>alert('Bus Record Added!'); window.location.href='add_bus.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="container-fluid">
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 15px;">
        <h5 class="fw-bold text-primary mb-4">Add New Bus Details</h5>
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="small fw-bold">Bus No</label>
                    <input type="text" name="bus_no" class="form-control" placeholder="e.g. LEC-101" required>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Driver Name</label>
                    <input type="text" name="driver_name" class="form-control" placeholder="Name" required>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Helper Name</label>
                    <input type="text" name="helper_name" class="form-control" placeholder="Name" required>
                </div>
                <div class="col-md-3">
                    <label class="text-white d-block">.</label>
                    <button type="submit" name="save_bus" class="btn btn-primary w-100 fw-bold">SAVE BUS</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
        <h5 class="fw-bold mb-3">Bus Fleet Database</h5>
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>Bus No</th><th>Driver</th><th>Helper</th><th>Status</th><th class="text-end">Action</th></tr>
            </thead>
            <tbody>
                <?php
                $res = mysqli_query($conn, "SELECT * FROM buses ORDER BY id DESC");
                while($row = mysqli_fetch_assoc($res)){
                    echo "<tr>
                            <td class='fw-bold'>".$row['bus_no']."</td>
                            <td>".$row['driver_name']."</td>
                            <td>".$row['helper_name']."</td>
                            <td><span class='badge bg-success-subtle text-success border border-success-subtle'>".$row['status']."</span></td>
                            <td class='text-end'>
                                <a href='add_bus.php?del_bus=".$row['id']."' class='text-danger' onclick='return confirm(\"Delete record?\")'><i class='fas fa-trash'></i></a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>