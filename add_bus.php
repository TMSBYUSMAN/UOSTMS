<?php 
include('db_connect.php'); 
include('header.php'); 

// Initialize variables for Edit mode
$edit_mode = false;
$edit_id = "";
$bus_no_val = "";
$driver_val = "";
$helper_val = "";

// 1. DELETE LOGIC (Now safer due to Cascade Delete)
if(isset($_GET['del_bus'])){
    $id = $_GET['del_bus'];
    if(mysqli_query($conn, "DELETE FROM buses WHERE id = $id")){
        echo "<script>alert('Bus deleted successfully!'); window.location.href='add_bus.php';</script>";
    } else {
        echo "<script>alert('Error deleting bus!');</script>";
    }
}

// 2. FETCH DATA FOR EDITING
if(isset($_GET['edit_bus'])){
    $edit_mode = true;
    $edit_id = $_GET['edit_bus'];
    $edit_res = mysqli_query($conn, "SELECT * FROM buses WHERE id = $edit_id");
    $edit_data = mysqli_fetch_assoc($edit_res);
    
    $bus_no_val = $edit_data['bus_no'];
    $driver_val = $edit_data['driver_name'];
    $helper_val = $edit_data['helper_name'];
}

// 3. SAVE OR UPDATE LOGIC
if(isset($_POST['save_bus'])){
    $bus_no = mysqli_real_escape_string($conn, $_POST['bus_no']);
    $driver = mysqli_real_escape_string($conn, $_POST['driver_name']);
    $helper = mysqli_real_escape_string($conn, $_POST['helper_name']);
    
    if(isset($_POST['update_id']) && !empty($_POST['update_id'])){
        // UPDATE EXISTING RECORD
        $id = $_POST['update_id'];
        $query = "UPDATE buses SET bus_no='$bus_no', driver_name='$driver', helper_name='$helper' WHERE id=$id";
        $msg = "Bus Record Updated!";
    } else {
        // INSERT NEW RECORD
        $query = "INSERT INTO buses (bus_no, driver_name, helper_name, status) 
                  VALUES ('$bus_no', '$driver', '$helper', 'Active')";
        $msg = "Bus Record Added!";
    }
    
    if(mysqli_query($conn, $query)){
        echo "<script>alert('$msg'); window.location.href='add_bus.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 15px;">
        <h5 class="fw-bold text-primary mb-4">
            <i class="fas fa-bus me-2"></i><?php echo $edit_mode ? "Edit Bus Details" : "Add New Bus Details"; ?>
        </h5>
        <form method="POST">
            <input type="hidden" name="update_id" value="<?php echo $edit_id; ?>">
            
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="small fw-bold">Bus No</label>
                    <input type="text" name="bus_no" class="form-control" placeholder="e.g. LEC-101" value="<?php echo $bus_no_val; ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Driver Name</label>
                    <input type="text" name="driver_name" class="form-control" placeholder="Name" value="<?php echo $driver_val; ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Helper Name</label>
                    <input type="text" name="helper_name" class="form-control" placeholder="Name" value="<?php echo $helper_val; ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="text-white d-block">.</label>
                    <button type="submit" name="save_bus" class="btn <?php echo $edit_mode ? 'btn-success' : 'btn-primary'; ?> w-100 fw-bold shadow-sm">
                        <?php echo $edit_mode ? "UPDATE BUS" : "SAVE BUS"; ?>
                    </button>
                    <?php if($edit_mode): ?>
                        <a href="add_bus.php" class="btn btn-light w-100 mt-2 btn-sm border">Cancel Edit</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
        <h5 class="fw-bold mb-4 text-secondary"><i class="fas fa-list me-2"></i>Bus Fleet Database</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="py-3">Bus No</th>
                        <th class="py-3">Driver</th>
                        <th class="py-3">Helper</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM buses ORDER BY id DESC");
                    if(mysqli_num_rows($res) > 0){
                        while($row = mysqli_fetch_assoc($res)){
                            echo "<tr>
                                    <td class='fw-bold text-dark'>".$row['bus_no']."</td>
                                    <td>".$row['driver_name']."</td>
                                    <td>".$row['helper_name']."</td>
                                    <td><span class='badge bg-success-subtle text-success border border-success-subtle px-3'>".$row['status']."</span></td>
                                    <td class='text-end'>
                                        <a href='add_bus.php?edit_bus=".$row['id']."' class='btn btn-sm btn-outline-primary border-0 me-2'><i class='fas fa-edit'></i></a>
                                        <a href='add_bus.php?del_bus=".$row['id']."' class='btn btn-sm btn-outline-danger border-0' onclick='return confirm(\"Are you sure? This will delete all related records.\")'><i class='fas fa-trash'></i></a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-4 text-muted'>No buses registered yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>