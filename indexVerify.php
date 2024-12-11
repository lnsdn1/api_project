<?php
session_start();
require 'conx.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $Contact = $conn->real_escape_string($_POST['uContact']);
    $Password = $_POST['uPassword'];

    // Prepare and execute the query
    $sql = "SELECT * FROM users WHERE uContact = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $Contact);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($Password, $row['uPassword'])) {
            // Set session variables
            $_SESSION['uContact'] = $row['uContact'];
            $_SESSION['uType'] = $row['uType'];

            // Redirect based on user type
            if ($row['uType'] == 2) {
                header("Location: homepage.php");
            } else {
                header("Location: admin.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid email or password. Please try again.');</script>";
            echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 3000);</script>";
        }
    } else {
        echo "<script>alert('Invalid email or password. Please try again.');</script>";
        echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 3000);</script>";
    }

    $stmt->close();
}

$conn->close();
?>
