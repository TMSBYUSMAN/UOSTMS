<?php 
include('db_connect.php'); 

$name = $roll = $phone = $email = $route_id = "";

if(isset($_POST['register'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $roll = mysqli_real_escape_string($conn, $_POST['roll']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $route_id = mysqli_real_escape_string($conn, $_POST['route_id']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']); 

    if(!empty($name) && !empty($roll) && !empty($phone) && !empty($email) && !empty($route_id) && !empty($pass)){
        $check = mysqli_query($conn, "SELECT id FROM students WHERE roll_no = '$roll' OR email = '$email'");
        if(mysqli_num_rows($check) > 0){
            $msg = "<div class='alert alert-danger py-2 small border-0 mb-3' style='border-radius:10px;'>Error: Roll Number or Email already exists!</div>";
        } else {
            $sql = "INSERT INTO students (name, roll_no, contact, email, route_id, password, status) 
                    VALUES ('$name', '$roll', '$phone', '$email', '$route_id', '$pass', 'Pending')";
            if(mysqli_query($conn, $sql)){
                echo "<script>alert('Success! Account created. Waiting for Admin approval.'); window.location.href='student_login.php';</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration | UOS TMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background: linear-gradient(rgba(26, 35, 126, 0.8), rgba(26, 35, 126, 0.8)), url('https://img.freepik.com/free-vector/abstract-blue-geometric-shapes-background_1035-17545.jpg');
            background-size: cover; background-position: center; background-attachment: fixed;
            min-height: 100vh; display: flex; align-items: center; justify-content: center; 
            font-family: 'Poppins', sans-serif; padding: 20px;
        }
        .reg-card { 
            background: rgba(255, 255, 255, 0.95); padding: 35px; border-radius: 20px; 
            width: 100%; max-width: 550px; box-shadow: 0 20px 40px rgba(0,0,0,0.3); border-top: 5px solid #1a237e;
        }
        .btn-register { background: #1a237e; border: none; color: white; font-weight: bold; padding: 12px; transition: 0.4s; }
        .btn-register:hover { background: #0d47a1; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .form-control, .form-select { border-radius: 10px; padding: 10px; border: 1px solid #ddd; }
        
        .error-text { color: #dc3545; font-size: 0.75rem; font-weight: 500; display: none; margin-top: 4px; }
        .is-invalid { border-color: #dc3545 !important; }
        label { font-size: 0.85rem; font-weight: 600; color: #555; margin-left: 5px; }
    </style>
</head>
<body>

<div class="reg-card">
    <div class="text-center mb-4">
        <i class="fas fa-user-graduate fa-3x text-primary mb-2"></i>
        <h4 class="fw-bold text-dark">STUDENT REGISTRATION</h4>
        <p class="text-muted small">Transport Management System Portal</p>
    </div>
    
    <?php if(isset($msg)) echo $msg; ?>

    <form method="POST" id="registrationForm" novalidate>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label><i class="fas fa-user me-1"></i> Full Name</label>
                <input type="text" name="name" id="nameInput" class="form-control" placeholder="Enter Name" value="<?php echo $name; ?>">
                <span class="error-text" id="nameError">Name is required.</span>
            </div>
            <div class="col-md-6 mb-3">
                <label><i class="fas fa-id-card me-1"></i> Roll Number</label>
                <input type="text" name="roll" id="rollInput" class="form-control" placeholder="F24-1001" value="<?php echo $roll; ?>">
                <span class="error-text" id="rollError">Roll number is required.</span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label><i class="fas fa-phone me-1"></i> Mobile Number</label>
                <input type="text" name="phone" id="phoneInput" class="form-control" placeholder="03001234567" maxlength="11" value="<?php echo $phone; ?>">
                <span class="error-text" id="phoneError">Valid 11-digit number required.</span>
            </div>
            <div class="col-md-6 mb-3">
                <label><i class="fas fa-envelope me-1"></i> Email Address</label>
                <input type="email" name="email" id="emailInput" class="form-control" placeholder="name@gmail.com" value="<?php echo $email; ?>">
                <span class="error-text" id="emailError">Valid email (@gmail, etc) required.</span>
            </div>
        </div>
        
        <div class="mb-3">
            <label><i class="fas fa-bus me-1"></i> Select Your Route</label>
            <select name="route_id" id="routeInput" class="form-select">
                <option value="">-- Choose Route --</option>
                <?php 
                $res = mysqli_query($conn, "SELECT * FROM routes");
                while($r = mysqli_fetch_assoc($res)){ 
                    $selected = ($r['id'] == $route_id) ? "selected" : "";
                    echo "<option value='".$r['id']."' $selected>".$r['route_name']."</option>"; 
                }
                ?>
            </select>
            <span class="error-text" id="routeError">Please select your route.</span>
        </div>
        
        <div class="mb-4">
            <label><i class="fas fa-lock me-1"></i> Set Password</label>
            <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Min 6 characters">
            <span class="error-text" id="passError">Password must be at least 6 characters.</span>
        </div>

        <button type="submit" name="register" id="submitBtn" class="btn btn-register w-100 rounded-pill shadow-sm">
            CREATE ACCOUNT <i class="fas fa-check-circle ms-2"></i>
        </button>
        
        <div class="text-center mt-3 pt-2 border-top">
            <p class="small text-muted mb-0">Already have an account? <a href="student_login.php" class="login-link">Login Here</a></p>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registrationForm');
    const inputs = {
        nameInput: { error: 'nameError', check: (v) => v !== "" },
        rollInput: { error: 'rollError', check: (v) => v !== "" },
        phoneInput: { error: 'phoneError', check: (v) => /^03[0-9]{9}$/.test(v) },
        emailInput: { error: 'emailError', check: (v) => /^[a-zA-Z0-9._%+-]+@(gmail|outlook|hotmail|yahoo|icloud)\.com$/.test(v) },
        routeInput: { error: 'routeError', check: (v) => v !== "" },
        passwordInput: { error: 'passError', check: (v) => v.length >= 6 }
    };

    // --- Validation for SINGLE input ---
    function validateSingle(inputId) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(inputs[inputId].error);
        const val = input.value.trim();

        if (!inputs[inputId].check(val)) {
            input.classList.add('is-invalid');
            error.style.display = 'block';
            return false;
        } else {
            input.classList.remove('is-invalid');
            error.style.display = 'none';
            return true;
        }
    }

    // --- Blur Event: Sirf us box ko check karo jis se user bahar nikla ---
    Object.keys(inputs).forEach(id => {
        document.getElementById(id).addEventListener('blur', () => {
            validateSingle(id);
        });
    });

    // --- Submit Event: Button dabane par saari details check karo ---
    form.addEventListener('submit', function (e) {
        let isAllValid = true;
        Object.keys(inputs).forEach(id => {
            if (!validateSingle(id)) isAllValid = false;
        });

        if (!isAllValid) {
            e.preventDefault();
            alert('Please complete all details correctly before creating an account.');
        }
    });
});
</script>

</body>
</html>