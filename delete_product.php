<?php
session_start();

// Redirect to login if not logged in or not an admin
if (!isset($_SESSION['uContact']) || $_SESSION['uType'] != 1) {
    header("Location: index.php");
    exit();
}

// Connect to database
require 'conx.php';

// Handle delete product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $id = $_POST['id'];
    
    // Fetch the product name before deleting for logging
    $sql = "SELECT product_name FROM products WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $productName = $product['product_name'];
    
    $stmt->close();

    // Delete the product
    $sql = "DELETE FROM products WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        log_audit_trail($conn, $_SESSION['uContact'], "Deleted product: $productName");
        echo "Product deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
