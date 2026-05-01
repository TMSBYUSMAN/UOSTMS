<?php
session_start();
include('db_connect.php');

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM students WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        if($row['status'] == 'Approved'){
            $_SESSION['std_id'] = $row['id'];
            $_SESSION['std_name'] = $row['name'];
            header('location:student_dashboard.php');
        } else {
            $error = "Your account is pending admin approval!";
        }
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login | UNI-TMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #1a237e 0%, #283593 100%); height: 100vh; display: flex; align-items: center; justify-content: center; font-family: sans-serif; }
        .login-card { width: 400px; background: white; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.3); padding: 30px; }
        .btn-primary { background: #1a237e; border: none; padding: 10px; border-radius: 8px; font-weight: bold; }
        .btn-outline-secondary { border-radius: 8px; padding: 10px; font-size: 14px; }
        .form-control { border-radius: 8px; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <div class="bg-primary text-white d-inline-block p-3 rounded-circle mb-3">
            <i class="fas fa-bus fa-2x"></i>
        </div>
        <h4 class="fw-bold">Student Portal</h4>
        <p class="text-muted small">Login to manage your bus pass</p>
    </div>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger py-2 small text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="small fw-bold mb-1">Email Address</label>
            <input type="email" name="email" class="form-control shadow-none" placeholder="name@example.com" required>
        </div>

        <div class="mb-4">
            <label class="small fw-bold mb-1">Password</label>
            <input type="password" name="password" class="form-control shadow-none" placeholder="••••••••" required>
        </div>

        <button type="submit" name="login" class="btn btn-primary w-100 mb-3">LOGIN</button>
        
        <div class="text-center">
            <hr>
            <p class="small text-muted mb-2">Don't have an account?</p>
            <a href="register.php" class="btn btn-outline-secondary w-100 fw-bold">CREATE NEW ACCOUNT</a>
        </div>
    </form>
</div>

</body>
</html>