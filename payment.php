<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['uContact'])) {
    header("Location: index.php");
    exit();
}

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// Connect to database
require 'conx.php';

// Calculate total amount and set product details in session if not already set
if (!isset($_SESSION['product_names']) || !isset($_SESSION['product_prices'])) {
    $_SESSION['product_names'] = [];
    $_SESSION['product_prices'] = [];

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $sql = "SELECT product_name, product_price FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $_SESSION['product_names'][$product_id] = $row['product_name'];
            $_SESSION['product_prices'][$product_id] = $row['product_price'];
        }
    }
}

// Calculate total amount
// Calculate total amount
$total_amount = 0;
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    if (isset($_SESSION['product_prices'][$product_id])) {
        $total_amount += $quantity * $_SESSION['product_prices'][$product_id];
    } else {
        // Handle the case where $_SESSION['product_prices'][$product_id] is not set
        echo "Product price for ID $product_id is not set.<br>";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['submit_payment'])) {
    $pickup_date = $_POST['pickup_date'];
    $pickup_time = $_POST['pickup_time'];

    $_SESSION['pickup_date'] = $pickup_date;
    $_SESSION['pickup_time'] = $pickup_time;
}

// Handle form submission
if (isset($_POST['submit_payment'])) {
    $payment_method = $_POST['payment_method'];
    $reference_number = $_POST['reference_number'];
    $receipt = $_FILES['receipt']['name'];

    // Ensure the uploads directory exists
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Save the uploaded file
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["receipt"]["name"]);
    if (move_uploaded_file($_FILES["receipt"]["tmp_name"], $target_file)) {
        // Save order details to the database
        $uContact = $_SESSION['uContact'];
        $pickup_date = $_SESSION['pickup_date'];
        $pickup_time = $_SESSION['pickup_time'];
        $order_query = "INSERT INTO orders (uContact, total_amount, pickup_date, pickup_time, payment_method, reference_number, receipt) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($order_query);
        $stmt->bind_param("sdsssss", $uContact, $total_amount, $pickup_date, $pickup_time, $payment_method, $reference_number, $target_file);
        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;

            // Insert order items
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $product_name = $_SESSION['product_names'][$product_id];
                $product_price = $_SESSION['product_prices'][$product_id];
                $total_price = $quantity * $product_price;

                $order_item_query = "INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, total_price) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($order_item_query);
                $stmt->bind_param("iisdid", $order_id, $product_id, $product_name, $product_price, $quantity, $total_price);
                $stmt->execute();
            }

            // Store order details in session for the success page
            $_SESSION['order_details'] = [
                'total_amount' => $total_amount,
                'pickup_date' => $pickup_date,
                'pickup_time' => $pickup_time,
                'payment_method' => $payment_method,
                'reference_number' => $reference_number,
                'receipt' => $target_file
            ];

            // Clear the cart
            unset($_SESSION['cart']);
            unset($_SESSION['product_prices']);
            unset($_SESSION['product_names']);

            header("Location: order_success.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beyond D' Crust - payment</title>

    <link rel="website icon" type="png" href="resource/logoicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        body {
            background-image: url('resource/bghomee.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh; /* Ensures the body takes full viewport height */
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin-bottom: 60px;
            position: relative;
            gap: 35px; /* Add gap between the menu and cart */
        }
        .content {
            border: 2px solid #fddf07;
            background-color: rgba(102, 0, 1, 0.6); /* White background with some transparency */
            padding: 20px;
            border-radius: 10px;
            width: 45%;
            margin: 100px;
            margin-left: 537px;
            position: relative;
        }
         .content label {
            color: white;
            text-shadow: 2px 2px 5px black;
         }
        
        .container h1 {
            color: white;
            text-shadow: 2px 2px 5px black;
        }
        .product {
            margin-bottom: 20px;
        }
        .pagination {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }
        .pagination.left {
            left: -50px;
        }
        .pagination.right {
            right: -50px;
        }
        .pagination a {
            display: inline-block;
            padding: 8px 16px;
            color: white;
            background-color: #7f0001;
            border: 2px solid #fddf07;
            border-radius: 50%;
            text-decoration: none;
        }
        .pagination a:hover {
            background-color: #fddf07;
        }
        .navbar {
            background-color: #7f0001;
            border-bottom: 3px solid #fddf07;
            margin-top: 0;
            position: relative;
            z-index: 2;
        }
        .navbar .navbar-text {
            color: white;
        }
        .nav-item a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            margin-top: 10px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 15px rgba(0, 0, 0, 25);
        }
        .nav-item a:hover {
            background-color: #fddf07;
            color: white;
            text-decoration: none;
        }
        .navbar .logo {
            color: #fff;
            font-weight: 600;
            font-size: 4rem;
            text-decoration: none;
            text-shadow: 2px 2px 15px rgba(0, 0, 0, 25);
        }
        .footer {
            background-color: #7f0001;
            border-top: 3px solid #fddf07;
            color: white;
            padding: 6px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 3;
        }
        .content h1 {
            color: white;
            text-shadow: 2px 2px 15px rgba(0, 0, 0, 25);
        }
        .cart-content {
            max-height: 550px; /* Adjust this value as needed */
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="logo"><b>BEYOND THE CRUST</b></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="homepage.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="homepage.php">Menu</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="content">
        <div class="container px-5 py-5">
            <h1 class="text-center mb-4">Payment</h1>
            <form action="payment.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="payment-method" class="form-label">Payment Method</label>
                    <select id="payment-method" name="payment_method" class="form-control" required>
                        <option value="gcash">GCash</option>
                        <option value="paymaya">PayMaya</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="total-amount" class="form-label">Total Amount</label>
                    <input type="text" id="total-amount" name="total_amount" class="form-control" value="<?php echo $total_amount; ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="reference-number" class="form-label">Reference Number</label>
                    <input type="text" id="reference-number" name="reference_number" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="receipt" class="form-label">Upload Receipt</label>
                    <input type="file" id="receipt" name="receipt" class="form-control" required>
                </div>
                <div class="text-center">
                    <button type="submit" name="submit_payment" class="btn btn-success">Submit Payment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="text-center p-3">
            &copy; 2024 Beyond the Crust
        </div>
    </footer>
</body>
</html>
<?php
$conn->close();
?>