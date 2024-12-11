<?php
session_start();

// Connect to database
require_once 'conx.php';

// Initialize cart items array if not already done
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart
$success_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    if (!isset($_SESSION['uContact'])) {
        echo json_encode(['status' => 'error', 'message' => 'You need to log in first.']);
        exit();
    }

    $product_id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    echo json_encode(['status' => 'success', 'message' => 'Product added to cart successfully!']);
    exit();
}

// Pagination logic
$limit = 5; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Fetch total number of products
$total_results = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
$total_pages = ceil($total_results / $limit);

// Fetch products for the current page
$sql = "SELECT * FROM products LIMIT $start, $limit";
$result = $conn->query($sql);

// Fetch products in the cart
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$product_ids = array_keys($cart_items);
$products = [];

if (!empty($product_ids)) {
    $ids = implode(',', array_fill(0, count($product_ids), '?'));
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($product_ids)), ...$product_ids);
    $stmt->execute();
    $result_cart = $stmt->get_result();
    while ($row = $result_cart->fetch_assoc()) {
        $products[$row['id']] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beyond D' Crust - homepage</title>

    <link rel="website icon" type="png" href="resource/logoicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            margin: 10px;
            position: relative;
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
    <script>
        $(document).ready(function() {
            $('form.add-to-cart').on('submit', function(event) {
                event.preventDefault(); // Prevent form from submitting normally
                var form = $(this);
                $.ajax({
                    url: 'homepage.php',
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status === 'error') {
                            alert(data.message);
                            window.location.href = 'index.php';
                        } else if (data.status === 'success') {
                            alert(data.message);
                            // Parse the new cart items and update the cart section
                            var newCartHtml = $(response).find('#cart').html();
                            $('#cart').html(newCartHtml);
                        }
                    }
                });
            });
        });
    </script>
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

    <div class="main-content">
        <div class="content">
            <!-- Pagination Left Arrow -->
            <?php if($page > 1): ?>
                <div class="pagination left">
                    <a href="homepage.php?page=<?php echo $page - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </div>
            <?php endif; ?>

            <h1 class="text-center mb-4">BEYOND THE CRUST MENU</h1>
            
            <!-- Success message -->
            <?php if ($success_message): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 col-sm-6 product">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                                <p class="card-text"><?php echo $row['product_description']; ?></p>
                                <p class="card-text"><strong>Price:</strong> <?php echo $row['product_price']; ?></p>
                                <form class="add-to-cart" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="action" value="add">
                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Pagination Right Arrow -->
            <?php if($page < $total_pages): ?>
                <div class="pagination right">
                    <a href="homepage.php?page=<?php echo $page + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="content" id="cart">
            <h1 class="text-center mb-4">Your Cart</h1>
            <div class="cart-content">
                <?php if (empty($cart_items)): ?>
                    <div class="alert alert-info" role="alert">
                        Your cart is empty.
                    </div>
                <?php else: ?>
                    <ul class="list-group mb-4">
                        <?php foreach ($cart_items as $id => $quantity): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1"><?php echo $products[$id]['product_name']; ?></h5>
                                    <p class="mb-1">Price: <?php echo $products[$id]['product_price']; ?></p>
                                    <p class="mb-1">Total: <?php echo $quantity * $products[$id]['product_price']; ?></p>
                                </div>
                                <div>
                                    <form action="update_cart.php" method="post" class="d-inline">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        <input type="hidden" name="action" value="update">
                                        <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="1" class="form-control d-inline w-auto">
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                    <form action="remove_from_cart.php" method="post" class="d-inline">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        <input type="hidden" name="action" value="remove">
                                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="text-center">
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
            </div>
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
