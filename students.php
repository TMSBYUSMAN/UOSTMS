<?php 
include('db_connect.php'); 
include('header.php'); 

// 1. APPROVE STUDENT LOGIC
if(isset($_GET['approve'])){
    $id = intval($_GET['approve']);
    mysqli_query($conn, "UPDATE students SET status = 'Approved' WHERE id = $id");
    echo "<script>alert('Student Approved!'); window.location.href='students.php';</script>";
}

// 2. DELETE STUDENT LOGIC
if(isset($_GET['delete_std'])){
    $id = intval($_GET['delete_std']);
    mysqli_query($conn, "DELETE FROM fees WHERE student_id = $id");
    if(mysqli_query($conn, "DELETE FROM students WHERE id = $id")){
        echo "<script>alert('Student Deleted!'); window.location.href='students.php';</script>";
    }
}

// --- NEW: RESET PHOTO LOGIC ---
if(isset($_GET['reset_pic'])){
    $id = intval($_GET['reset_pic']);
    
    // Pehle purani image ka naam nikalna taake file system se delete ho sake
    $res = mysqli_query($conn, "SELECT image FROM students WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    
    if(!empty($row['image'])){
        $file_path = "uploads/" . $row['image']; // Aapka image folder path
        if(file_exists($file_path)){
            unlink($file_path); // File delete karna
        }
    }
    
    // Database mein image column ko khali kar dena
    mysqli_query($conn, "UPDATE students SET image = '' WHERE id = $id");
    echo "<script>alert('Student Photo Reset Successfully!'); window.location.href='students.php';</script>";
}

// 3. ADD NEW STUDENT LOGIC
if(isset($_POST['add_student'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $roll = mysqli_real_escape_string($conn, $_POST['roll']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $route = $_POST['route_id'];
    
    $q = "INSERT INTO students (name, roll_no, email, password, contact, route_id, status) 
          VALUES ('$name', '$roll', '$email', '$password', '$contact', '$route', 'Approved')";
    
    if(mysqli_query($conn, $q)){
        echo "<script>alert('Student Registered Successfully!'); window.location.href='students.php';</script>";
    }
}
?>

<div class="container-fluid">
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 15px;">
        <h5 class="fw-bold text-primary mb-3"><i class="fas fa-plus-circle me-2"></i>Register New Student</h5>
        <form method="POST" class="row g-3">
            <div class="col-md-3">
                <label class="small fw-bold">Name</label>
                <input type="text" name="name" class="form-control" placeholder="Full Name" required>
            </div>
            <div class="col-md-2">
                <label class="small fw-bold">Roll No</label>
                <input type="text" name="roll" class="form-control" placeholder="F21-123" required>
            </div>
            <div class="col-md-3">
                <label class="small fw-bold">Email (Login ID)</label>
                <input type="email" name="email" class="form-control" placeholder="student@mail.com" required>
            </div>
            <div class="col-md-2">
                <label class="small fw-bold">Password</label>
                <input type="text" name="password" class="form-control" placeholder="Set Password" required>
            </div>
            <div class="col-md-2">
                <label class="small fw-bold">Contact</label>
                <input type="text" name="contact" class="form-control" placeholder="0300..." required>
            </div>
            <div class="col-md-3">
                <label class="small fw-bold">Route</label>
                <select name="route_id" class="form-select" required>
                    <option value="">-- Select Route --</option>
                    <?php 
                    $rs = mysqli_query($conn, "SELECT * FROM routes");
                    while($r = mysqli_fetch_assoc($rs)){ echo "<option value='".$r['id']."'>".$r['route_name']."</option>"; }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="d-block">&nbsp;</label>
                <button type="submit" name="add_student" class="btn btn-primary w-100 fw-bold">SAVE STUDENT</button>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
        <h5 class="fw-bold text-secondary mb-4">Manage Enrolled Students</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light small fw-bold text-uppercase">
                    <tr>
                        <th>Roll No</th>
                        <th>Name</th>
                        <th>Email / Password</th>
                        <th>Contact</th>
                        <th>Route</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $fetch = mysqli_query($conn, "SELECT students.*, routes.route_name FROM students LEFT JOIN routes ON students.route_id = routes.id ORDER BY id DESC");
                    while($row = mysqli_fetch_assoc($fetch)){
                        $badge = ($row['status'] == 'Approved') ? 'bg-success' : 'bg-warning text-dark';
                        ?>
                        <tr>
                            <td class='fw-bold'><?php echo $row['roll_no']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td class='small'><?php echo $row['email']; ?><br><span class='text-muted'>PW: <?php echo $row['password']; ?></span></td>
                            <td><?php echo $row['contact']; ?></td>
                            <td><?php echo $row['route_name']; ?></td>
                            <td><span class='badge <?php echo $badge; ?>'><?php echo $row['status']; ?></span></td>
                            <td class='text-end'>
                                <?php if($row['status'] == 'Pending'): ?>
                                    <a href='students.php?approve=<?php echo $row['id']; ?>' class="btn btn-sm btn-success me-1">Approve</a>
                                <?php endif; ?>

                                <a href='students.php?reset_pic=<?php echo $row['id']; ?>' class="btn btn-sm btn-outline-warning me-1" onclick="return confirm('Reset this student picture?')">
                                    <i class="fas fa-user-circle"></i> Reset Pic
                                </a>
                                
                                <a href='students.php?delete_std=<?php echo $row['id']; ?>' class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this student?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>