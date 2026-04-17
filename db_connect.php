<?php
$host = "localhost";
$user = "root";     // Default XAMPP user
$pass = "";         // Default XAMPP password is empty
$dbname = "university_tms";

// Creating Connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Checking Connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>