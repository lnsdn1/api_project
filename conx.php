<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lei";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

function log_audit_trail($conn, $user, $action) {
    $sql = "INSERT INTO audit_trail (user, action) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user, $action);
    $stmt->execute();
    $stmt->close();
}
?>