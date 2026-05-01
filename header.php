<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uni-TMS | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { margin: 0; padding: 0; background-color: #f4f7f6; font-family: 'Poppins', sans-serif; }
        
        .sidebar {
            width: 220px; 
            height: 100vh;
            background: #1a237e;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            color: white;
            overflow-y: auto; /* Scrollable sidebar if links increase */
        }

        .sidebar h4 { padding: 20px; font-weight: bold; border-bottom: 1px solid rgba(255,255,255,0.1); text-align: center; }
        .sidebar a { display: block; color: #cfd8dc; padding: 12px 20px; text-decoration: none; transition: 0.3s; }
        .sidebar a:hover { background: #283593; color: white; padding-left: 30px; }
        .sidebar i { margin-right: 10px; width: 20px; }

        /* Current Page Active Class */
        .sidebar a.active { background: #283593; color: white; border-left: 4px solid #ffeb3b; }

        /* Student Portal Link Styling */
        .portal-link { color: #ffeb3b !important; font-weight: bold; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 10px; }
        .portal-link:hover { background: #fbc02d !important; color: #000 !important; }

        .main-wrapper {
            margin-left: 225px; 
            padding: 20px;
            width: calc(100% - 225px);
        }
    </style>
</head>
<body>

<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<div class="sidebar">
    <h4><i class="fas fa-bus"></i> UOS-TMS</h4>
    <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>"><i class="fas fa-home"></i> Dashboard</a>
    <a href="add_bus.php" class="<?php echo ($current_page == 'add_bus.php') ? 'active' : ''; ?>"><i class="fas fa-bus-alt"></i> Manage Buses</a>
    <a href="add_route.php" class="<?php echo ($current_page == 'add_route.php') ? 'active' : ''; ?>"><i class="fas fa-route"></i> Manage Routes</a>
    <a href="drivers.php" class="<?php echo ($current_page == 'drivers.php') ? 'active' : ''; ?>"><i class="fas fa-user-tie"></i> Drivers</a>
    <a href="students.php" class="<?php echo ($current_page == 'students.php') ? 'active' : ''; ?>"><i class="fas fa-user-graduate"></i> Students</a>
    <a href="fees.php" class="<?php echo ($current_page == 'fees.php') ? 'active' : ''; ?>"><i class="fas fa-money-bill-wave"></i> Fees</a>
    
    <a href="view_complaints.php" class="<?php echo ($current_page == 'view_complaints.php') ? 'active' : ''; ?>">
        <i class="fas fa-exclamation-circle"></i> Complaints 
    </a>
    
    <a href="student_login.php" target="_blank" class="portal-link">
        <i class="fas fa-external-link-alt"></i> Student Portal
    </a>

    <a href="logout.php" class="mt-4 text-warning"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main-wrapper">