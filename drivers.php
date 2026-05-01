<?php 
include('db_connect.php'); 
include('header.php'); 

// Initialize variables for Edit mode
$edit_mode = false;
$edit_id = "";
$name_val = "";
$license_val = "";
$phone_val = "";

// 1. DELETE LOGIC
if(isset($_GET['del'])){
    $id = $_GET['del'];
    mysqli_query($conn, "DELETE FROM drivers WHERE id = $id");
    echo "<script>window.location.href='drivers.php';</script>";
}

// 2. FETCH DATA FOR EDITING
if(isset($_GET['edit_driver'])){
    $edit_mode = true;
    $edit_id = $_GET['edit_driver'];
    $edit_res = mysqli_query($conn, "SELECT * FROM drivers WHERE id = $edit_id");
    $edit_data = mysqli_fetch_assoc($edit_res);
    
    $name_val = $edit_data['name'];
    $license_val = $edit_data['license_no'];
    $phone_val = $edit_data['phone'];
}

// 3. SAVE OR UPDATE LOGIC
if(isset($_POST['save_driver'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $license = mysqli_real_escape_string($conn, $_POST['license']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    if(isset($_POST['update_id']) && !empty($_POST['update_id'])){
        // UPDATE EXISTING RECORD
        $id = $_POST['update_id'];
        $query = "UPDATE drivers SET name='$name', license_no='$license', phone='$phone' WHERE id=$id";
        $msg = "Driver Record Updated!";
    } else {
        // INSERT NEW RECORD
        $query = "INSERT INTO drivers (name, license_no, phone) VALUES ('$name', '$license', '$phone')";
        $msg = "Driver Record Added!";
    }
    
    if(mysqli_query($conn, $query)){
        echo "<script>alert('$msg'); window.location.href='drivers.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="container-fluid">
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 15px;">
        <h5 class="fw-bold text-warning mb-4">
            <i class="fas fa-user-tie me-2"></i>
            <?php echo $edit_mode ? "Edit Driver Details" : "Manage Drivers"; ?>
        </h5>
        <form method="POST">
            <input type="hidden" name="update_id" value="<?php echo $edit_id; ?>">
            
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="small fw-bold">Driver Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Full Name" value="<?php echo $name_val; ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">License No</label>
                    <input type="text" name="license" class="form-control" placeholder="License Number" value="<?php echo $license_val; ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Phone Number</label>
                    <input type="text" name="phone" class="form-control" placeholder="e.g. 03001234567" value="<?php echo $phone_val; ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="text-white d-block">.</label>
                    <button type="submit" name="save_driver" class="btn <?php echo $edit_mode ? 'btn-success' : 'btn-warning'; ?> w-100 fw-bold shadow-sm">
                        <?php echo $edit_mode ? "UPDATE" : "SAVE"; ?>
                    </button>
                    <?php if($edit_mode): ?>
                        <a href="drivers.php" class="btn btn-light w-100 mt-2 btn-sm">Cancel</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
        <h5 class="fw-bold mb-3 text-dark">Drivers List</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Driver Name</th>
                        <th>License No</th>
                        <th>Phone</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM drivers ORDER BY id DESC");
                    if(mysqli_num_rows($res) > 0){
                        while($row = mysqli_fetch_assoc($res)){
                            echo "<tr>
                                    <td class='fw-bold'>".$row['name']."</td>
                                    <td><span class='badge bg-light text-dark border'>".$row['license_no']."</span></td>
                                    <td>".$row['phone']."</td>
                                    <td class='text-end'>
                                        <a href='drivers.php?edit_driver=".$row['id']."' class='btn btn-sm text-primary me-2'>
                                            <i class='fas fa-edit'></i>
                                        </a>
                                        <a href='drivers.php?del=".$row['id']."' class='btn btn-sm text-danger' onclick='return confirm(\"Are you sure you want to delete this driver?\")'>
                                            <i class='fas fa-trash'></i>
                                        </a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center text-muted py-4'>No drivers found in the database.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>