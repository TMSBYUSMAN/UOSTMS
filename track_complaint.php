<?php 
session_start();
include('db_connect.php'); 

if(!isset($_SESSION['std_id'])){ header('location:student_login.php'); exit(); }
$std_id = $_SESSION['std_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track My Complaints</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary">Track My Complaints</h4>
        <a href="student_dashboard.php" class="btn btn-sm btn-secondary">Back to Dashboard</a>
    </div>

    <?php
    $res = mysqli_query($conn, "SELECT * FROM complaints WHERE student_id = '$std_id' ORDER BY id DESC");
    if(mysqli_num_rows($res) > 0){
        while($row = mysqli_fetch_assoc($res)){
            $status_color = 'warning';
            if($row['status'] == 'Under Process') $status_color = 'info';
            if($row['status'] == 'Resolved') $status_color = 'success';
            ?>
            <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-bold mb-1 text-uppercase text-muted" style="font-size: 0.8rem;">Ticket #<?php echo $row['id']; ?></h6>
                        <span class="badge bg-<?php echo $status_color; ?>"><?php echo $row['status']; ?></span>
                    </div>
                    <h5 class="fw-bold"><?php echo $row['subject']; ?></h5>
                    <p class="text-muted small"><?php echo $row['message']; ?></p>
                    
                    <?php if($row['admin_reply']): ?>
                        <div class="p-3 bg-light border-start border-3 border-success mt-3" style="border-radius: 0 8px 8px 0;">
                            <strong class="small d-block text-success">ADMIN RESPONSE:</strong>
                            <p class="mb-0 small fst-italic"><?php echo $row['admin_reply']; ?></p>
                        </div>
                    <?php else: ?>
                        <div class="text-muted small mt-2 fst-italic">Waiting for admin to review...</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php } 
    } else {
        echo "<div class='text-center py-5'><p class='text-muted'>No complaints found.</p><a href='submit_complaint.php' class='btn btn-primary btn-sm'>New Complaint</a></div>";
    } ?>
</div>
</body>
</html>