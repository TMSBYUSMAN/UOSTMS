<?php
session_start();
include('db_connect.php');
$error = "";

if(isset($_POST['std_login'])){
    $roll = mysqli_real_escape_string($conn, $_POST['roll_no']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // Student ko Roll No aur Phone No se verify karein
    $q = "SELECT * FROM students WHERE roll_no = '$roll' AND contact = '$phone'";
    $res = mysqli_query($conn, $q);
    
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);
        $_SESSION['std_id'] = $row['id'];
        $_SESSION['std_name'] = $row['name'];
        $_SESSION['std_roll'] = $row['roll_no'];
        header('location:student_dashboard.php');
        exit();
    } else {
        $error = "Ghalat Roll Number ya Mobile Number!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Portal | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .login-card { background: white; padding: 40px; border-radius: 20px; width: 100%; max-width: 400px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); }
        .btn-student { background: #764ba2; border: none; color: white; font-weight: bold; transition: 0.3s; }
        .btn-student:hover { background: #667eea; transform: translateY(-2px); }
    </style>
</head>
<body>
<div class="login-card text-center">
    <i class="fas fa-user-graduate fa-3x text-primary mb-3"></i>
    <h3 class="fw-bold">STUDENT PORTAL</h3>
    <p class="text-muted small mb-4">Apna Voucher Download Karne ke liye Login Karein</p>

    <?php if($error != ""): ?>
        <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3 text-start">
            <label class="small fw-bold">Roll Number</label>
            <input type="text" name="roll_no" class="form-control" placeholder="e.g. F24-101" required>
        </div>
        <div class="mb-4 text-start">
            <label class="small fw-bold">Mobile Number (Verified)</label>
            <input type="text" name="phone" class="form-control" placeholder="03XXXXXXXXX" required>
        </div>
        <button type="submit" name="std_login" class="btn btn-student w-100 p-2 rounded-pill shadow-sm">LOGIN TO PORTAL</button>
    </form>
</div>
</body>
</html>