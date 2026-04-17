<?php
// Session start karni padti hai taaki use khatam kiya ja sake
session_start();

// Saara session data delete kar do
session_unset();
session_destroy();

// Wapas login page par bhej do
header("Location: login.php");
exit();
?>