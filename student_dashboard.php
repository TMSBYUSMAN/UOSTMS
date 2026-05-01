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
            // Save to Database
            mysqli_query($conn, "UPDATE students SET image = '$file_name' WHERE id = '$std_id'");
            echo "<script>alert('Photo Uploaded and Locked successfully!'); window.location.href='student_dashboard.php';</script>";
        }
    }
}

// Fetch latest student data
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
        .default-avatar { width: 150px; height: 150px; line-height: 150px; font-size: 80px; color: #1a237e; background: #e8eaf6; border-radius: 50%; margin: 0 auto 15px; }
        .preview-img { width: 120px; height: 120px; object-fit: cover; border-radius: 10px; display: none; margin: 10px auto; border: 2px dashed #1a237e; }
        .card-custom { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-4 shadow-sm">
    <div class="container">
        <span class="fw-bold text-white"><i class="fas fa-bus-alt me-2"></i> STUDENT PORTAL</span>
        <div class="d-flex">
            <a href="track_complaint.php" class="btn btn-info btn-sm me-2 text-white fw-bold">
                <i class="fas fa-search me-1"></i> Track My Tickets
            </a>
            
            <a href="submit_complaint.php" class="btn btn-outline-light btn-sm me-2">
                <i class="fas fa-headset me-1"></i> Help & Complaints
            </a>
            
            <a href="student_logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="profile-card">
                <?php if(!empty($std['image']) && file_exists("uploads/".$std['image'])): ?>
                    <img src="uploads/<?php echo $std['image']; ?>" class="std-img mb-3">
                    <h5 class="fw-bold mb-0"><?php echo $std['name']; ?></h5>
                    <p class="text-muted small mb-3">Roll No: <?php echo $std['roll_no']; ?></p>
                    <div class="badge bg-success w-100 p-2"><i class="fas fa-lock me-1"></i> Profile Locked</div>
                <?php else: ?>
                    <div class="default-avatar"><i class="fas fa-user-circle"></i></div>
                    <h6 class="text-primary fw-bold mb-3">Upload Profile Photo</h6>
                    <img id="img_preview" class="preview-img">
                    
                    <form id="uploadForm" method="POST" enctype="multipart/form-data">
                        <input type="file" name="profile_img" id="fileInput" class="form-control form-control-sm mb-2" accept="image/*" required>
                        <button type="button" id="confirmBtn1" class="btn btn-primary btn-sm w-100 mb-2" style="display:none;">
                            Yes, this is my photo
                        </button>
                        <button type="submit" name="upload_photo" id="finalSubmit" style="display:none;"></button>
                    </form>
                    <p class="text-danger small mt-2">Note: Once uploaded, you cannot change it without Admin permission.</p>
                <?php endif; ?>
            </div>

            <div class="card mt-4 card-custom p-3 bg-light text-center">
                <p class="small text-muted mb-2">Facing issues with the bus or fees?</p>
                <a href="submit_complaint.php" class="btn btn-sm btn-outline-primary fw-bold">Submit a Query</a>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Fee Vouchers & Bus Card</h5>
                    <i class="fas fa-file-invoice-dollar text-muted fa-lg"></i>
                </div>
                
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Month</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $fees = mysqli_query($conn, "SELECT * FROM fees WHERE student_id = '$std_id' ORDER BY id DESC");
                            if(mysqli_num_rows($fees) > 0){
                                while($f = mysqli_fetch_assoc($fees)){
                                    $is_paid = ($f['status'] == 'Paid');
                                    ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo $f['month_name']; ?></td>
                                        <td>
                                            <span class="badge <?php echo ($is_paid ? 'bg-success' : 'bg-warning text-dark'); ?>">
                                                <?php echo $f['status']; ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <?php if($is_paid && !empty($std['image'])): ?>
                                                <a href='print_card.php?fee_id=<?php echo $f['id']; ?>' class='btn btn-sm btn-dark shadow-sm'>
                                                    <i class='fas fa-id-card me-1'></i> Download Card
                                                </a>
                                            <?php elseif($is_paid && empty($std['image'])): ?>
                                                <span class='text-danger small fw-bold'>Upload photo to get card</span>
                                            <?php else: ?>
                                                <a href='print_challan.php?id=<?php echo $f['id']; ?>' class='btn btn-sm btn-outline-primary'>
                                                    <i class="fas fa-print me-1"></i> Print Challan
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php } 
                            } else {
                                echo "<tr><td colspan='3' class='text-center py-4 text-muted small'>No vouchers found.</td></tr>";
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Image Preview Logic
document.getElementById('fileInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('img_preview');
            preview.src = e.target.result;
            preview.style.display = 'block';
            document.getElementById('confirmBtn1').style.display = 'block';
            // Hide default icon during preview
            const defaultAvatar = document.querySelector('.default-avatar');
            if(defaultAvatar) defaultAvatar.style.display = 'none';
        }
        reader.readAsDataURL(file);
    }
});

// Double Confirmation Logic
document.getElementById('confirmBtn1').addEventListener('click', function() {
    if(confirm("FINAL CHECK: Is this photo correct? You cannot change it later!")) {
        if(confirm("Are you 100% sure?")) {
            document.getElementById('finalSubmit').click();
        }
    }
});
</script>

</body>
</html>