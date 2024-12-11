<?php
session_start();

// Destroy the session
session_destroy();

// Redirect to the login page (index.php)
header("Location: index.php");
exit();
?>
