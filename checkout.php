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

// Fetch products in the cart
$cart_items = $_SESSION['cart'];
$product_ids = array_keys($cart_items);
$products = array();

if (!empty($product_ids)) {
    $ids = implode(',', array_fill(0, count($product_ids), '?'));
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($product_ids)), ...$product_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $products[$row['id']] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beyond D' Crust - checkout</title>

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
            text-shadow: 2px 2px 15px rgba(0, 0, 0, 25);
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
            <h1 class="text-center mb-4">Order Details</h1>
            <ul class="list-group mb-4">
                <?php foreach ($cart_items as $id => $quantity): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1"><?php echo $products[$id]['product_name']; ?></h5>
                            <p class="mb-1">Price: <?php echo $products[$id]['product_price']; ?></p>
                            <p class="mb-1">Quantity: <?php echo $quantity; ?></p>
                            <p class="mb-1">Total: <?php echo $quantity * $products[$id]['product_price']; ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <form action="page_payment2.php" method="post">
                <div class="mb-3">
                    <label for="pickup-date" class="form-label">Pick-Up Date</label>
                    <input type="date" id="pickup-date" name="pickup_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="pickup-time" class="form-label">Pick-Up Time</label>
                    <input type="time" id="pickup-time" name="pickup_time" class="form-control" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success">Confirm Order</button>
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
