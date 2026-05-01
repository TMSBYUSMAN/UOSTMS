<?php 
session_start();
include('db_connect.php'); 

// Error reporting on karein taake pata chale kya masla hai
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION['std_id'])){ 
    header('location:student_login.php'); 
    exit(); 
}

$std_id = $_SESSION['std_id'];

// Student data fetch karein
$std_res = mysqli_query($conn, "SELECT * FROM students WHERE id = '$std_id'");
$std_data = mysqli_fetch_assoc($std_res);

if(isset($_POST['send_query'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    
    // Insert query
    $q = "INSERT INTO complaints (student_id, name, email, phone, subject, message, status) 
          VALUES ('$std_id', '$name', '$email', '$phone', '$subject', '$msg', 'Pending')";
    
    if(mysqli_query($conn, $q)){
        echo "<script>alert('Complaint Submitted Successfully!'); window.location.href='student_dashboard.php';</script>";
    } else {
        // Agar error aaye to wo dikhaye ga
        die("Database Error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Complaint</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card mx-auto shadow" style="max-width: 600px; border-radius: 15px;">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0">Submit Your Complaint</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="name" value="<?php echo $std_data['name']; ?>">
                <input type="hidden" name="email" value="<?php echo $std_data['email']; ?>">
                
                <div class="mb-3">
                    <label class="small fw-bold">Phone Number</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Subject</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Describe Issue</label>
                    <textarea name="message" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" name="send_query" class="btn btn-primary w-100">Submit Complaint</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>