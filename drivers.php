<?php 
include('db_connect.php'); include('header.php'); 

if(isset($_GET['del'])){
    $id = $_GET['del'];
    mysqli_query($conn, "DELETE FROM drivers WHERE id = $id");
    echo "<script>window.location.href='drivers.php';</script>";
}

if(isset($_POST['save_driver'])){
    $n = $_POST['name']; $l = $_POST['license']; $p = $_POST['phone'];
    mysqli_query($conn, "INSERT INTO drivers (name, license_no, phone) VALUES ('$n', '$l', '$p')");
    echo "<script>window.location.href='drivers.php';</script>";
}
?>
<div class="container-fluid">
    <div class="card border-0 shadow-sm p-4 mb-4">
        <h5 class="fw-bold text-warning">Manage Drivers</h5>
        <form method="POST" class="row g-3">
            <div class="col-md-4"><input type="text" name="name" class="form-control" placeholder="Name" required></div>
            <div class="col-md-3"><input type="text" name="license" class="form-control" placeholder="License No" required></div>
            <div class="col-md-3"><input type="text" name="phone" class="form-control" placeholder="Phone" required></div>
            <div class="col-md-2"><button type="submit" name="save_driver" class="btn btn-warning w-100">Save</button></div>
        </form>
    </div>
    <div class="card border-0 shadow-sm p-4">
        <table class="table table-hover">
            <thead><tr><th>Name</th><th>License</th><th>Phone</th><th class="text-end">Action</th></tr></thead>
            <tbody>
                <?php
                $res = mysqli_query($conn, "SELECT * FROM drivers ORDER BY id DESC");
                while($row = mysqli_fetch_assoc($res)){
                    echo "<tr><td>".$row['name']."</td><td>".$row['license_no']."</td><td>".$row['phone']."</td><td class='text-end'><a href='drivers.php?del=".$row['id']."' class='text-danger' onclick='return confirm(\"Delete?\")'><i class='fas fa-trash'></i></a></td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>