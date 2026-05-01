<?php 
include('db_connect.php'); 
include('header.php'); 

// Initialize variables for Edit mode
$edit_mode = false;
$edit_id = "";
$bus_val = "";
$route_val = "";
$city_val = "";

// 1. DELETE LOGIC
if(isset($_GET['del_route'])){
    $id = $_GET['del_route'];
    if(mysqli_query($conn, "DELETE FROM routes WHERE id = $id")){
        echo "<script>alert('Route deleted successfully!'); window.location.href='add_route.php';</script>";
    }
}

// 2. FETCH DATA FOR EDITING
if(isset($_GET['edit_route'])){
    $edit_mode = true;
    $edit_id = $_GET['edit_route'];
    $edit_res = mysqli_query($conn, "SELECT * FROM routes WHERE id = $edit_id");
    $edit_data = mysqli_fetch_assoc($edit_res);
    
    $bus_val = $edit_data['bus_number'];
    $route_val = $edit_data['route_name'];
    $city_val = $edit_data['city'];
}

// 3. SAVE OR UPDATE LOGIC
if(isset($_POST['save_route'])){
    $bus_no = mysqli_real_escape_string($conn, $_POST['bus_number']);
    $route = mysqli_real_escape_string($conn, $_POST['route_name']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    
    if(isset($_POST['update_id']) && !empty($_POST['update_id'])){
        // UPDATE EXISTING RECORD
        $id = $_POST['update_id'];
        $query = "UPDATE routes SET bus_number='$bus_no', route_name='$route', city='$city' WHERE id=$id";
        $msg = "Route Details Updated!";
    } else {
        // INSERT NEW RECORD
        $query = "INSERT INTO routes (bus_number, route_name, city) VALUES ('$bus_no', '$route', '$city')";
        $msg = "New Route Added!";
    }
    
    if(mysqli_query($conn, $query)){
        echo "<script>alert('$msg'); window.location.href='add_route.php';</script>";
    }
}
?>

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 15px;">
        <h5 class="fw-bold text-success mb-4">
            <i class="fas fa-route me-2"></i>
            <?php echo $edit_mode ? "Edit Route Assignment" : "Assign Bus to Route"; ?>
        </h5>
        
        <div class="mb-3">
            <label class="small fw-bold d-block mb-2 text-muted">Quick Select City:</label>
            <button type="button" class="btn btn-sm btn-outline-success me-1 mb-1" onclick="setCityAndRoute('Pakpattan')">+ Pakpattan</button>
            <button type="button" class="btn btn-sm btn-outline-success me-1 mb-1" onclick="setCityAndRoute('Okara')">+ Okara</button>
            <button type="button" class="btn btn-sm btn-outline-success me-1 mb-1" onclick="setCityAndRoute('Arifwala')">+ Arifwala</button>
            <button type="button" class="btn btn-sm btn-outline-success me-1 mb-1" onclick="setCityAndRoute('Chichawatni')">+ Chichawatni</button>
            <button type="button" class="btn btn-sm btn-outline-success me-1 mb-1" onclick="setCityAndRoute('Sahiwal')">+ Sahiwal</button>
        </div>

        <form method="POST">
            <input type="hidden" name="update_id" value="<?php echo $edit_id; ?>">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="small fw-bold">Select Bus</label>
                    <select name="bus_number" class="form-select" required>
                        <option value="">-- Choose Bus --</option>
                        <?php 
                        $buses = mysqli_query($conn, "SELECT bus_no FROM buses");
                        while($b = mysqli_fetch_assoc($buses)){
                            $selected = ($b['bus_no'] == $bus_val) ? "selected" : "";
                            echo "<option value='".$b['bus_no']."' $selected>".$b['bus_no']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">City Name</label>
                    <input type="text" name="city" id="city_input" class="form-control" placeholder="e.g. Sahiwal" value="<?php echo $city_val; ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold">Route Description</label>
                    <input type="text" name="route_name" id="route_input" class="form-control" placeholder="e.g. Name of City" value="<?php echo $route_val; ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="text-white d-block">.</label>
                    <button type="submit" name="save_route" class="btn <?php echo $edit_mode ? 'btn-primary' : 'btn-success'; ?> w-100 fw-bold shadow-sm">
                        <?php echo $edit_mode ? "UPDATE" : "SAVE"; ?>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
        <h5 class="fw-bold mb-4 text-secondary"><i class="fas fa-list-ul me-2"></i>Active Route Assignments</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr class="small text-uppercase">
                        <th class="py-3">Bus Number</th>
                        <th class="py-3">City</th>
                        <th class="py-3">Assigned Route</th>
                        <th class="py-3 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM routes ORDER BY id DESC");
                    while($row = mysqli_fetch_assoc($res)){
                        echo "<tr>
                                <td class='fw-bold text-primary'>".$row['bus_number']."</td>
                                <td><span class='badge bg-light text-dark border'>".$row['city']."</span></td>
                                <td>".$row['route_name']."</td>
                                <td class='text-end'>
                                    <a href='add_route.php?edit_route=".$row['id']."' class='btn btn-sm btn-outline-primary border-0 me-2'>
                                        <i class='fas fa-edit'></i>
                                    </a>
                                    <a href='add_route.php?del_route=".$row['id']."' class='btn btn-sm btn-outline-danger border-0' onclick='return confirm(\"Delete this assignment?\")'>
                                        <i class='fas fa-trash'></i>
                                    </a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Yeh function dono inputs ko ek saath fill karega
function setCityAndRoute(name) {
    document.getElementById('city_input').value = name;
    document.getElementById('route_input').value = name;
}
</script>