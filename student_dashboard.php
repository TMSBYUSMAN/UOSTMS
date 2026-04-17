<?php
session_start();
include('db_connect.php');

if(!isset($_SESSION['std_id'])){ 
    header('location:student_login.php'); 
    exit(); 
}

$std_id = $_SESSION['std_id'];

// --- PHOTO UPLOAD LOGIC ---
if(isset($_POST['upload_photo'])){
    $target_dir = "uploads/";
    $file_name = time() . "_" . basename($_FILES["profile_img"]["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if it's a real image
    $check = getimagesize($_FILES["profile_img"]["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file)) {
            // Database mein save karein
            mysqli_query($conn, "UPDATE students SET image = '$file_name' WHERE id = '$std_id'");
            echo "<script>alert('Photo Uploaded and Locked successfully!'); window.location.href='student_dashboard.php';</script>";
        }
    }
}

// Student ka latest data nikalne ke liye
$std_data = mysqli_query($conn, "SELECT * FROM students WHERE id = '$std_id'");
$std = mysqli_fetch_assoc($std_data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Uni-TMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Poppins', sans-serif; }
        .navbar { background: #1a237e !important; }
        .profile-card { background: white; border-radius: 15px; padding: 20px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .std-img { width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 5px solid #e3f2fd; }
        .preview-img { width: 120px; height: 120px; object-fit: cover; border-radius: 10px; display: none; margin: 10px auto; border: 2px dashed #1a237e; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-4 text-white">
    <div class="container">
        <span class="fw-bold"><i class="fas fa-bus-alt me-2"></i> STUDENT PORTAL</span>
        <a href="student_logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="profile-card">
                <?php if($std['image']): ?>
                    <img src="uploads/<?php echo $std['image']; ?>" class="std-img mb-3">
                    <h5 class="fw-bold"><?php echo $std['name']; ?></h5>
                    <p class="text-muted small">Roll No: <?php echo $std['roll_no']; ?></p>
                    <div class="badge bg-success w-100 p-2"><i class="fas fa-lock me-1"></i> Profile Locked</div>
                <?php else: ?>
                    <h6 class="text-primary fw-bold mb-3">Upload Profile Photo</h6>
                    <img id="img_preview" class="preview-img">
                    
                    <form id="uploadForm" method="POST" enctype="multipart/form-data">
                        <input type="file" name="profile_img" id="fileInput" class="form-control form-control-sm mb-2" accept="image/*" required>
                        
                        <button type="button" id="confirmBtn1" class="btn btn-primary btn-sm w-100 mb-2" style="display:none;">
                            Yes, this is my photo
                        </button>
                        
                        <button type="submit" name="upload_photo" id="finalSubmit" style="display:none;"></button>
                    </form>
                    <p class="text-danger small mt-2">Note: Ek baar upload hone ke baad aap ise change nahi kar sakenge.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius:15px;">
                <h5 class="fw-bold mb-4">My Fee Vouchers & Bus Card</h5>
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Month</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $fees = mysqli_query($conn, "SELECT * FROM fees WHERE student_id = '$std_id' ORDER BY id DESC");
                        while($f = mysqli_fetch_assoc($fees)){
                            $is_paid = ($f['status'] == 'Paid');
                            echo "<tr>
                                    <td>".$f['month_name']."</td>
                                    <td><span class='badge ".($is_paid ? 'bg-success' : 'bg-warning text-dark')."'>".$f['status']."</span></td>
                                    <td>";
                                    
                            if($is_paid && $std['image']){
                                echo "<a href='print_card.php?fee_id=".$f['id']."' class='btn btn-sm btn-dark'><i class='fas fa-id-card me-1'></i> Download Bus Card</a>";
                            } elseif($is_paid && !$std['image']) {
                                echo "<span class='text-danger small'>Please upload photo to get card</span>";
                            } else {
                                echo "<a href='print_challan.php?id=".$f['id']."' class='btn btn-sm btn-outline-primary'>Print Challan</a>";
                            }
                            
                            echo "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// STEP 2: Preview Logic
document.getElementById('fileInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('img_preview');
            preview.src = e.target.result;
            preview.style.display = 'block';
            document.getElementById('confirmBtn1').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

// STEP 3: Double Confirmation Logic
document.getElementById('confirmBtn1').addEventListener('click', function() {
    if(confirm("FINAL CHECK: Kya ye photo sahi hai? Upload hone ke baad ye kabhi change nahi hogi!")) {
        if(confirm("Are you 100% sure? Iske baad Admin hi ise reset kar sakega.")) {
            document.getElementById('finalSubmit').click();
        }
    }
});
</script>

</body>
</html>