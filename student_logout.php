<?php
session_start();
session_destroy(); // Student ki session khatam karein
header("Location: student_login.php"); // Wapis student login par bhejein
exit();
?>