<?php 
include('db_connect.php'); 
include('header.php'); 

// 1. DELETE LOGIC
if(isset($_GET['del_route'])){
    $id = $_GET['del_route'];
    mysqli_query($conn, "DELETE FROM routes WHERE id = $id");
    echo "<script>window.location.href='add_route.php';</script>";
}

// 2. SAVE LOGIC
if(isset($_POST['save_route'])){
    $bus_no = mysqli_real_escape_string($conn, $_POST['bus_number']);
    $route = mysqli_real_escape_string($conn, $_POST['route_name']);
    
    $query = "INSERT INTO routes (bus_number, route_name) VALUES ('$bus_no', '$route')";
    
    if(mysqli_query($conn, $query)){
        echo "<script>alert('Route Assigned to Bus!'); window.location.href='add_route.php';</script>";
    }
}
?>

<div class="container-fluid">
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 15px;">
        <h5 class="fw-bold text-success mb-4"><i class="fas fa-route me-2"></i>Assign Bus to Route</h5>
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="small fw-bold">Select Bus</label>
                    <select name="bus_number" class="form-select" required>
                        <option value="">-- Choose Bus --</option>
                        <?php 
                        // Buses table se data uthana
                        $buses = mysqli_query($conn, "SELECT bus_no FROM buses");
                        while($b = mysqli_fetch_assoc($buses)){
                            echo "<option value='".$b['bus_no']."'>".$b['bus_no']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="small fw-bold">Route Description (Where is it going?)</label>
                    <input type="text" name="route_name" class="form-control" placeholder="e.g. Sahiwal to Arifwala" required>
                </div>
                <div class="col-md-3">
                    <label class="text-white d-block">.</label>
                    <button type="submit" name="save_route" class="btn btn-success w-100 fw-bold shadow-sm">SAVE ROUTE</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
        <h5 class="fw-bold mb-3">Active Assignments</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-light">
                    <tr class="small text-uppercase">
                        <th>Bus Number</th>
                        <th>Assigned Route</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM routes ORDER BY id DESC");
                    if(mysqli_num_rows($res) > 0){
                        while($row = mysqli_fetch_assoc($res)){
                            echo "<tr>
                                    <td class='fw-bold text-primary'>".$row['bus_number']."</td>
                                    <td>".$row['route_name']."</td>
                                    <td class='text-end'>
                                        <a href='add_route.php?del_route=".$row['id']."' class='btn btn-sm text-danger' onclick='return confirm(\"Delete this assignment?\")'>
                                            <i class='fas fa-trash'></i>
                                        </a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-muted py-4'>No routes assigned yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>