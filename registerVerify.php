<?php
require 'conx.php';

$Fname = $_POST['uFname'];
$Lname = $_POST['uLname'];
$Contact = $_POST['uContact'];
$Password = $_POST['uPassword'];

$hashedPassword = password_hash($Password, PASSWORD_BCRYPT);

$emailCheckQuery = "SELECT * FROM users WHERE uContact = '$Contact'";
$emailCheckResult = $conn->query($emailCheckQuery);

if ($emailCheckResult->num_rows > 0) {
    echo "<script>alert('Email or Phone Number already exists. Please choose a different email or phone number.');</script>";
    echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 3000);</script>";
} else {
    $sql = "INSERT INTO users (uFname, uLname, uContact, uPassword) VALUES ('$Fname', '$Lname', '$Contact', '$hashedPassword')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Sign up successful');</script>";
        echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 3000);</script>";
        exit();
    } else {
        echo "Register failed: " . $conn->error;
    }
}

$conn->close();
?>