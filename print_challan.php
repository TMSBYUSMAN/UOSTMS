<?php 
include('db_connect.php');

// ID check karein
if(!isset($_GET['id'])){ die("Error: Voucher ID missing!"); }

$id = $_GET['id'];

// Simple Query taaki error na aaye
$query = "SELECT f.*, s.name as std_name, s.roll_no 
          FROM fees f 
          LEFT JOIN students s ON f.student_id = s.id 
          WHERE f.id = $id";

$data = mysqli_query($conn, $query);

if(mysqli_num_rows($data) > 0){
    $row = mysqli_fetch_assoc($data);
} else {
    die("Error: Voucher data not found in database!");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Challan - <?php echo $row['challan_no']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Arial', sans-serif; background: #f8f9fa; }
        .challan-box { border: 2px solid #000; padding: 15px; background: #fff; min-height: 550px; }
        .copy-tag { background: #000; color: #fff; padding: 2px 10px; font-weight: bold; font-size: 10px; }
        .header-section { border-bottom: 2px solid #000; margin-bottom: 10px; padding-bottom: 5px; }
        @media print { .btn-print { display: none; } body { background: #fff; } }
    </style>
</head>
<body>

<div class="container-fluid py-4">
    <div class="text-center mb-4 btn-print">
        <button onclick="window.print()" class="btn btn-danger btn-lg shadow">
            <i class="fas fa-print"></i> PRINT / DOWNLOAD VOUCHER
        </button>
    </div>
    
    <div class="row g-0">
        <?php 
        $copies = ["BANK COPY", "OFFICE COPY", "STUDENT COPY"];
        foreach($copies as $copy): 
        ?>
        <div class="col-4 challan-box border-end">
            <div class="header-section text-center">
                <div class="copy-tag text-uppercase mb-2 d-inline-block"><?php echo $copy; ?></div>
                <h5 class="fw-bold mb-0">UNIVERSITY TRANSPORT</h5>
                <p class="small mb-0">Fee Voucher - <?php echo $row['month_name']; ?></p>
            </div>
            
            <div class="d-flex justify-content-between mb-3">
                <span><strong>Challan:</strong> <?php echo $row['challan_no']; ?></span>
                <span><strong>Date:</strong> <?php echo date('d-M-Y'); ?></span>
            </div>

            <table class="table table-sm table-borderless small mb-4">
                <tr><td width="30%"><strong>Roll No:</strong></td><td><?php echo $row['roll_no']; ?></td></tr>
                <tr><td><strong>Name:</strong></td><td><?php echo $row['std_name']; ?></td></tr>
            </table>

            <table class="table table-bordered text-center small">
                <thead class="table-light">
                    <tr><th>Description</th><th>Amount (PKR)</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-start">Transport Monthly Fee</td>
                        <td><?php echo number_format($row['amount'], 2); ?></td>
                    </tr>
                    <tr class="fw-bold">
                        <td class="text-end">TOTAL PAYABLE:</td>
                        <td><?php echo number_format($row['amount'], 2); ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-4 small">
                <p><strong>Instructions:</strong><br>
                1. Payable at any branch of HBL/UBL.<br>
                2. Non-refundable after payment.<br>
                3. Keep student copy for bus card issuance.</p>
            </div>

            <div class="mt-5 pt-4 d-flex justify-content-between text-center small">
                <div style="border-top: 1px solid #000; width: 40%;">Cashier Signature</div>
                <div style="border-top: 1px solid #000; width: 40%;">Authorized Officer</div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>