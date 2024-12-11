<?php
session_start();

// Redirect to login if not logged in or not an admin
if (!isset($_SESSION['uContact']) || $_SESSION['uType'] != 1) {
    header("Location: index.php");
    exit();
}

// Connect to database
require 'conx.php';

// Handle add product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    
    $sql = "INSERT INTO products (product_name, product_price, product_description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $price, $description);
    if ($stmt->execute()) {
        log_audit_trail($conn, $_SESSION['uContact'], "Added new product: $name");
        echo "Product added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch products
$result = $conn->query("SELECT * FROM products");

// Other parts of your code remain the same...
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beyond D' Crust - products</title>

    <link rel="website icon" type="png" href="resource/logoicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
            margin-top: 120px;
            margin-left: 535px;

            position: relative;
        }

        .container p {
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
        .content h1, h2, label {
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
                            <a class="nav-link" href="admin.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="products.php">Products</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="reports.php">Sales</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="audit_trail.php">Reports</a>
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
            <h1 class="text-center mb-4">Manage Products</h1>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" name="add_product">Add Product</button>
            </form>
            <hr>
            <h2 class="text-center mb-4">Product List</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['product_price']; ?></td>
                        <td><?php echo $row['product_description']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
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
