<?php
session_start();
include('db_connect.php');

// Agar student login nahi ya fee_id missing hai to bahar nikal do
if(!isset($_SESSION['std_id']) || !isset($_GET['fee_id'])){
    header('location:student_login.php');
    exit();
}

$std_id = $_SESSION['std_id'];
$fee_id = mysqli_real_escape_string($conn, $_GET['fee_id']);

// Naya Query: City column ke saath
$query = "SELECT students.*, fees.month_name, fees.status, routes.route_name, routes.city 
          FROM students 
          JOIN fees ON students.id = fees.student_id 
          LEFT JOIN routes ON students.route_id = routes.id 
          WHERE students.id = '$std_id' AND fees.id = '$fee_id'";

$res = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($res);

// Security Check: Card sirf Paid vouchers ka hi show hoga
if($data['status'] != 'Paid'){
    echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
            <h2 style='color:red;'>Access Denied!</h2>
            <p>Bus Card is only available after fee verification.</p>
            <a href='student_dashboard.php'>Go Back</a>
          </div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bus Pass - <?php echo $data['name']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #d1d8e0; font-family: 'Roboto', sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        
        .bus-card {
            width: 500px; height: 280px; background: white; border-radius: 18px;
            overflow: hidden; position: relative; box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            display: flex; border: 2px solid #1a237e;
        }

        .card-left {
            width: 35%; background: #1a237e; display: flex; flex-direction: column;
            align-items: center; justify-content: center; color: white; position: relative;
        }

        .card-left img {
            width: 110px; height: 110px; border-radius: 12px; border: 3px solid #ffd700;
            object-fit: cover; margin-bottom: 10px; background: white;
        }

        .card-left .pass-id { font-size: 10px; font-family: 'Orbitron', sans-serif; color: #ffd700; }

        .card-right { width: 65%; padding: 20px; position: relative; background-image: radial-gradient(#f0f0f0 1px, transparent 1px); background-size: 15px 15px; }

        .header-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; }
        .uni-logo { font-weight: 800; color: #1a237e; font-size: 18px; line-height: 1; }
        .uni-logo span { font-size: 10px; display: block; color: #777; letter-spacing: 2px; }

        .chip { width: 40px; height: 30px; background: linear-gradient(135deg, #ffd700, #b8860b); border-radius: 5px; }

        .student-name { font-size: 22px; font-weight: 900; color: #333; margin: 0; text-transform: uppercase; border-bottom: 2px solid #1a237e; display: inline-block; }
        
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px; }
        .info-item { display: flex; flex-direction: column; }
        .info-label { font-size: 9px; font-weight: bold; color: #888; text-transform: uppercase; }
        .info-text { font-size: 12px; font-weight: bold; color: #1a237e; }

        .verified-seal { position: absolute; bottom: 20px; right: 20px; font-size: 40px; color: rgba(40, 167, 69, 0.2); transform: rotate(-20deg); }

        .valid-badge {
            position: absolute; top: 70px; right: -30px; background: #ffd700;
            color: #000; padding: 5px 40px; transform: rotate(45deg);
            font-size: 10px; font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .no-print-btn { margin-bottom: 20px; }
        .btn-print { background: #333; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; text-decoration: none; }

        @media print {
            .no-print-btn { display: none; }
            body { background: white; height: auto; padding: 0; }
            .bus-card { box-shadow: none; border: 1px solid #1a237e; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="no-print-btn">
    <button onclick="window.print()" class="btn-print"><i class="fas fa-print me-2"></i> PRINT TRANSPORT ID CARD</button>
    <a href="student_dashboard.php" class="btn-print" style="background:#777; margin-left:10px;">BACK</a>
</div>

<div class="bus-card">
    <div class="valid-badge">ACTIVE PASS</div>
    
    <div class="card-left">
        <img src="uploads/<?php echo $data['image']; ?>" alt="Student Photo">
        <div class="pass-id">PASS# <?php echo date('Y')."-".$data['id']; ?></div>
        <div style="font-size: 8px; margin-top: 40px; opacity: 0.7;">TRANSPORT DEPT.</div>
    </div>

    <div class="card-right">
        <div class="header-top">
            <div class="uni-logo">
                UNI-TMS<span>TRANSPORT SYSTEM</span>
            </div>
            <div class="chip"></div>
        </div>

        <h2 class="student-name"><?php echo $data['name']; ?></h2>
        
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Student Roll No</span>
                <span class="info-text"><?php echo $data['roll_no']; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">City & Route</span>
                <span class="info-text">
                    <?php 
                    // Naya City logic
                    if(!empty($data['city'])){
                        echo $data['city'] . " (" . ($data['route_name'] ?? 'General') . ")";
                    } else {
                        echo $data['route_name'] ?? 'General';
                    }
                    ?>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Validity Month</span>
                <span class="info-text"><?php echo $data['month_name']; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Status</span>
                <span class="info-text" style="color: green;">VERIFIED PAID</span>
            </div>
        </div>

        <i class="fas fa-shield-halved verified-seal"></i>

        <div style="margin-top: 15px; border-top: 1px dashed #ccc; padding-top: 5px; font-size: 8px; color: #999;">
            <i class="fas fa-info-circle me-1"></i> This card must be carried at all times during travel.
        </div>
    </div>
</div>

</body>
</html>