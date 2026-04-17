<?php 
include('db_connect.php'); 
include('header.php'); 

// 1. DELETE STUDENT LOGIC
if(isset($_GET['delete_std'])){
    $id = $_GET['delete_std'];
    
    // Purani photo delete karna (Optional but clean)
    $res = mysqli_query($conn, "SELECT image FROM students WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    if($row['image']){ unlink("uploads/" . $row['image']); }

    mysqli_query($conn, "DELETE FROM students WHERE id = $id");
    echo "<script>window.location.href='students.php';</script>";
}

// 2. RESET PHOTO LOGIC (Admin Power)
if(isset($_GET['reset_photo'])){
    $id = $_GET['reset_photo'];
    
    // Folder se file delete karein
    $res = mysqli_query($conn, "SELECT image FROM students WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    if($row['image']){
        unlink("uploads/" . $row['image']);
    }

    // Database mein image null kar dein
    mysqli_query($conn, "UPDATE students SET image = NULL WHERE id = $id");
    echo "<script>alert('Student photo has been reset!'); window.location.href='students.php';</script>";
}

// SAVE LOGIC
if(isset($_POST['register_student'])){
    $name = mysqli_real_escape_string($conn, $_POST['s_name']);
    $roll = mysqli_real_escape_string($conn, $_POST['roll_no']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $route_id = mysqli_real_escape_string($conn, $_POST['route_id']);
    
    $query = "INSERT INTO students (name, roll_no, contact, route_id) VALUES ('$name', '$roll', '$contact', '$route_id')";
    if(mysqli_query($conn, $query)){
        echo "<script>alert('Student Registered Successfully!'); window.location.href='students.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// SEARCH LOGIC
$search_where = "";
if(!empty($_GET['search'])){
    $s = mysqli_real_escape_string($conn, $_GET['search']);
    $search_where = " WHERE students.name LIKE '%$s%' OR students.roll_no LIKE '%$s%' OR students.contact LIKE '%$s%' ";
}
?>

<div class="container-fluid">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                <h5 class="fw-bold text-primary mb-4"><i class="fas fa-user-plus me-2"></i>Enroll Student</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="small fw-bold">Full Name</label>
                        <input type="text" name="s_name" class="form-control" placeholder="e.g. Ali Ahmed" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold">Roll No</label>
                        <input type="text" name="roll_no" class="form-control" placeholder="e.g. F24-101" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold">Mobile Number (Password)</label>
                        <input type="text" name="contact" class="form-control" placeholder="03001234567" required>
                    </div>
                    <div class="mb-4">
                        <label class="small fw-bold">Assigned Route</label>
                        <select name="route_id" class="form-select" required>
                            <option value="">-- Select Route --</option>
                            <?php 
                            $r_list = mysqli_query($conn, "SELECT id, route_name FROM routes");
                            while($r = mysqli_fetch_assoc($r_list)){ echo "<option value='".$r['id']."'>".$r['route_name']."</option>"; }
                            ?>
                        </select>
                    </div>
                    <button type="submit" name="register_student" class="btn btn-primary w-100 fw-bold shadow-sm">REGISTER STUDENT</button>
                </form>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                <div class="d-flex justify-content-between mb-4 align-items-center">
                    <h5 class="fw-bold m-0 text-secondary">Student Directory</h5>
                    <form method="GET" class="d-flex shadow-sm" style="border-radius: 5px; overflow: hidden;">
                        <input type="text" name="search" class="form-control form-control-sm border-0" placeholder="Search Name/Roll/Phone...">
                        <button type="submit" class="btn btn-sm btn-primary border-0"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light text-uppercase small fw-bold">
                            <tr>
                                <th>Roll No</th>
                                <th>Name</th>
                                <th>Contact (Password)</th>
                                <th>Route</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $fetch = mysqli_query($conn, "SELECT students.*, routes.route_name FROM students LEFT JOIN routes ON students.route_id = routes.id $search_where ORDER BY students.id DESC");
                            if(mysqli_num_rows($fetch) > 0){
                                while($row = mysqli_fetch_assoc($fetch)){
                                    echo "<tr>
                                            <td class='fw-bold text-primary'>".$row['roll_no']."</td>
                                            <td>".$row['name']."</td>
                                            <td>".$row['contact']."</td>
                                            <td><span class='badge bg-light text-dark border'>".($row['route_name'] ?? 'Not Assigned')."</span></td>
                                            <td class='text-end'>
                                                <a href='students.php?reset_photo=".$row['id']."' class='btn btn-sm btn-outline-warning me-1' title='Reset Photo' onclick='return confirm(\"Reset this student photo?\")'>
                                                    <i class='fas fa-camera-rotate'></i>
                                                </a>
                                                <a href='students.php?delete_std=".$row['id']."' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Are you sure you want to delete this student?\")'>
                                                    <i class='fas fa-trash-alt'></i>
                                                </a>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center py-4 text-muted small'>No records found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>