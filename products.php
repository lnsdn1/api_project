<?php
session_start();

// Redirect to login if not logged in or not an admin
if (!isset($_SESSION['uContact']) || $_SESSION['uType'] != 1) {
    header("Location: index.php");
    exit();
}

// Connect to database
require 'conx.php';

// Fetch all products
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$sql = "SELECT * FROM products WHERE product_name LIKE '%$filter%' OR product_name LIKE 'P%'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beyond D' Crust - Products</title>

    <link rel="icon" type="image/png" href="resource/logoicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-image: url('resource/bghomee.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 60px;
            padding-bottom: 60px;
            gap: 35px;
        }
        .content {
            background-color: rgba(102, 0, 1, 0.8);
            border: 2px solid #fddf07;
            border-radius: 10px;
            padding: 10px;
            width: 1300px;
            margin: auto;
            max-height: 80vh; /* Set a max height to allow for scrolling */
            overflow-y: auto; /* Enable vertical scrolling */
        }
        .container p, .container h1 {
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }
        .table {
            background-color: #ffffff;
            color: #000000;
            border-radius: 8px;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .navbar {
            background-color: #7f0001;
            border-bottom: 3px solid #fddf07;
            z-index: 1000;
        }
        .navbar .navbar-brand {
            color: #fff;
            font-size: 2rem;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }
        .navbar .nav-link {
            color: #fff;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }
        .nav-link:hover {
            background-color: #fddf07;
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
        .filter-form {
            margin-bottom: 20px;
        }
        .filter-form input {
            display: inline-block;
            width: calc(100% - 120px);
        }
        .filter-form button {
            display: inline-block;
            width: 100px;
        }
        .btn-add {
            margin-bottom: 20px;
            float: right;
        }
        .btn-back {
            margin-top: 20px;
            float: left;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Beyond D' Crust</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="products.php">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="reports.php">Sales</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="audit_trail.php">Audit Trail</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content">
            <div class="container px-5 py-5">
                <h1 class="text-center mb-4">Product List</h1>
                
                <!-- Add Product Button -->
                <div class="d-flex justify-content-between mb-3">
                    <a href="add_product.php" class="btn btn-success">Add Product</a>
                </div>

                <!-- Filter Form -->
                <form class="filter-form mb-3" method="GET" action="products.php">
                    <div class="input-group">
                        <input type="text" class="form-control" name="filter" placeholder="Search for products..." value="<?php echo htmlspecialchars($filter); ?>">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>

                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['product_price']; ?></td>
                                <td><?php echo $row['product_description']; ?></td>
                                <td>
                                    <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                
                <!-- Back Button -->
                <div class="d-flex justify-content-end">
                    <a href="admin.php" class="btn btn-secondary btn-back">Back</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Beyond D' Crust. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
<?php
$conn->close();
?>
