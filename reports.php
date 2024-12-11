<?php
session_start();

// Redirect to login if not logged in or not an admin
if (!isset($_SESSION['uContact']) || $_SESSION['uType'] != 1) {
    header("Location: index.php");
    exit();
}

// Connect to database
require 'conx.php';

// Pagination setup
$limit = 10; // Number of entries to show per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch order summary with pagination
$sql = "SELECT product_name, SUM(quantity) as total_quantity, SUM(total_price) as total_revenue 
        FROM order_items 
        GROUP BY product_name 
        ORDER BY total_revenue DESC 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Fetch all products for search functionality
$all_sql = "SELECT product_name, SUM(quantity) as total_quantity, SUM(total_price) as total_revenue 
            FROM order_items 
            GROUP BY product_name 
            ORDER BY total_revenue DESC";
$all_result = $conn->query($all_sql);
$all_products = [];
while ($row = $all_result->fetch_assoc()) {
    $all_products[] = $row;
}

// Fetch total number of records
$total_sql = "SELECT COUNT(DISTINCT product_name) AS total FROM order_items";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_pages = ceil($total_row['total'] / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beyond D' Crust - Sales</title>

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
            padding: 60px 20px;
            gap: 35px;
        }
        .content {
            background-color: rgba(102, 0, 1, 0.8);
            border: 2px solid #fddf07;
            border-radius: 10px;
            padding: 20px;
            width: 1100px;
            
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container p, .container h1, .container h2 {
            color: white;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
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
            font-size: 4rem;
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
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        .pagination .page-item .page-link {
            color: #7f0001;
        }
        .pagination .page-item .page-link:hover {
            background-color: #fddf07;
            border-color: #7f0001;
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
        .search-container {
            margin-bottom: 20px;
        }
        .search-container input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
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
                            <a class="nav-link" href="products.php">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="reports.php">Sales</a>
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
                <h1 class="text-center mb-4">Sales Reports</h1>
                
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Search for product names...">
                </div>

                <table class="table table-bordered" id="salesTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>Product Name</th>
                            <th>Total Quantity Sold</th>
                            <th>Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['total_quantity']; ?></td>
                                <td>₱<?php echo number_format($row['total_revenue'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item <?php echo ($page <= 1 ? 'disabled' : ''); ?>">
                            <a class="page-link" href="?page=<?php echo ($page - 1); ?>" tabindex="-1">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($i == $page ? 'active' : ''); ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($page >= $total_pages ? 'disabled' : ''); ?>">
                            <a class="page-link" href="?page=<?php echo ($page + 1); ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
      <div class="text-center p-3">
        &copy; 2024 Beyond the Crust
      </div>
    </footer>

    <script>
        const allProducts = <?php echo json_encode($all_products); ?>;

        document.getElementById('searchInput').addEventListener('keyup', function() {
            const filter = this.value.toUpperCase();
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '';

            const filteredProducts = allProducts.filter(product => 
                product.product_name.toUpperCase().includes(filter)
            );

            filteredProducts.forEach(product => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${product.product_name}</td>
                    <td>${product.total_quantity}</td>
                    <td>₱${parseFloat(product.total_revenue).toFixed(2)}</td>
                `;
                tableBody.appendChild(row);
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>
