<?php
session_start(); // Session sab se upar hona chahiye
include('db_connect.php');

$error = ""; 

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($conn, $_POST['user']);
    $password = mysqli_real_escape_string($conn, $_POST['pass']);
    
    // Query to check user
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $res = mysqli_query($conn, $query);

    if($res && mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);
        $_SESSION['admin_user'] = $row['username']; // Session variable ka naam 'admin_user' rakha hai
        header('location:index.php'); 
        exit();
    } else {
        $error = "Ghalat Username ya Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>University TMS | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=1350&q=80');
            background-size: cover; background-position: center; height: 100vh;
            display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 20px;
            padding: 40px; width: 380px; box-shadow: 0 15px 35px rgba(0,0,0,0.5); color: white; text-align: center;
        }
        .bus-icon {
            font-size: 50px; color: #00d2ff; margin-bottom: 15px; display: inline-block;
            animation: moveBus 3s infinite linear;
        }
        @keyframes moveBus {
            0% { transform: translateX(-20px); }
            50% { transform: translateX(20px); }
            100% { transform: translateX(-20px); }
        }
        .form-control {
            background: rgba(255, 255, 255, 0.1) !important; border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: white !important; padding: 12px; margin-bottom: 20px;
        }
        .btn-login {
            background: linear-gradient(45deg, #00d2ff, #3a7bd5); border: none; font-weight: bold; width: 100%; padding: 12px;
        }
    </style>
</head>
<body>
<div class="login-card">
    <div class="bus-icon"><i class="fas fa-bus"></i></div>
    <h3 class="fw-bold mb-4">UNI-TMS LOGIN</h3>
    <?php if($error != ""): ?>
        <div class="alert alert-danger py-2 small mb-3"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="user" class="form-control" placeholder="Username" required>
        <input type="password" name="pass" class="form-control" placeholder="Password" required>
        <button type="submit" name="login" class="btn btn-login text-white rounded-pill">LOGIN NOW</button>
    </form>
</div>
</body>
</html>