<?php 
include('db_connect.php'); 
include('header.php'); 

// 1. DELETE VOUCHER LOGIC
if(isset($_GET['delete_id'])){
    $id = $_GET['delete_id'];
    $del = mysqli_query($conn, "DELETE FROM fees WHERE id = '$id'");
    if($del){
        echo "<script>alert('Voucher Deleted!'); window.location.href='fees.php';</script>";
    }
}

// 2. MARK AS PAID LOGIC (Naya Izafa)
if(isset($_GET['mark_paid'])){
    $id = $_GET['mark_paid'];
    $upd = mysqli_query($conn, "UPDATE fees SET status = 'Paid' WHERE id = '$id'");
    if($upd){
        echo "<script>window.location.href='fees.php';</script>";
    }
}

// 3. GENERATE VOUCHER LOGIC
if(isset($_POST['gen_voucher'])){
    $std_id = mysqli_real_escape_string($conn, $_POST['std_id']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $month = mysqli_real_escape_string($conn, $_POST['month']);
    $challan_no = "TMS" . date('is') . rand(10, 99); 

    $q = "INSERT INTO fees (student_id, amount, month_name, challan_no, status) 
          VALUES ('$std_id', '$amount', '$month', '$challan_no', 'Pending')";
    
    if(mysqli_query($conn, $q)){
        echo "<script>alert('Voucher Generated!'); window.location.href='fees.php';</script>";
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                <h5 class="fw-bold text-primary mb-4"><i class="fas fa-file-invoice-dollar me-2"></i>Generate New Voucher</h5>
                <form method="POST" class="row g-3">
                    <div class="col-md-5">
                        <label class="small fw-bold">Search & Select Student (Name/Roll No)</label>
                        <select name="std_id" id="student_select" class="form-select select2" required>
                            <option value="">-- Start typing name or roll no --</option>
                            <?php 
                            $stds = mysqli_query($conn, "SELECT id, name, roll_no FROM students");
                            while($s = mysqli_fetch_assoc($stds)){ 
                                echo "<option value='".$s['id']."'>".$s['roll_no']." - ".$s['name']."</option>"; 
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small fw-bold">Month</label>
                        <input type="text" name="month" class="form-control" value="<?php echo date('F Y'); ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label class="small fw-bold">Amount</label>
                        <input type="number" name="amount" class="form-control" placeholder="PKR" required>
                    </div>
                    <div class="col-md-3">
                        <label class="d-block">&nbsp;</label>
                        <button type="submit" name="gen_voucher" class="btn btn-primary w-100 fw-bold">GENERATE VOUCHER</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0">Generated Vouchers</h5>
                    <div class="input-group" style="width: 300px;">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search"></i></span>
                        <input type="text" id="tableSearch" class="form-control border-start-0" placeholder="Quick Search Roll No...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="voucherTable">
                        <thead class="table-light">
                            <tr>
                                <th>Challan</th>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Month</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $history = mysqli_query($conn, "SELECT fees.*, students.name, students.roll_no 
                                                           FROM fees 
                                                           JOIN students ON fees.student_id = students.id 
                                                           ORDER BY fees.id DESC");
                            while($h = mysqli_fetch_assoc($history)){
                                $status_clr = ($h['status'] == 'Paid') ? 'bg-success' : 'bg-warning text-dark';
                                echo "<tr>
                                        <td class='fw-bold text-secondary'>".$h['challan_no']."</td>
                                        <td class='roll-no'>".$h['roll_no']."</td>
                                        <td>".$h['name']."</td>
                                        <td>".$h['month_name']."</td>
                                        <td class='fw-bold'>Rs. ".number_format($h['amount'])."</td>
                                        <td><span class='badge $status_clr px-3'>".$h['status']."</span></td>
                                        <td class='text-end'>";
                                        
                                        // AGAR STATUS PENDING HAI TO PAID KA BUTTON DIKHAYEIN
                                        if($h['status'] == 'Pending'){
                                            echo "<a href='fees.php?mark_paid=".$h['id']."' class='btn btn-sm btn-success me-1' onclick=\"return confirm('Is this fee paid?')\">
                                                    <i class='fas fa-check'></i> Paid
                                                  </a>";
                                        }

                                        echo "<a href='print_challan.php?id=".$h['id']."' target='_blank' class='btn btn-sm btn-dark me-1'>
                                                <i class='fas fa-print'></i>
                                              </a>
                                              <a href='fees.php?delete_id=".$h['id']."' onclick=\"return confirm('Are you sure you want to delete this voucher?')\" class='btn btn-sm btn-outline-danger'>
                                                <i class='fas fa-trash'></i>
                                              </a>
                                        </td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Dropdown Search (Select2)
    $('#student_select').select2({
        placeholder: "Search by Name or Roll Number",
        allowClear: true
    });

    // Table Search Logic for Roll No
    $("#tableSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#voucherTable tbody tr").filter(function() {
            $(this).toggle($(this).find('.roll-no').text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>