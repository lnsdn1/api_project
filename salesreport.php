<?php require('conx.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1 class="mt-5 mb-4">Sales Report</h1>
    <p>Report generated on <?php echo date('Y-m-d'); ?></p>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr><th>Order ID</th><th>Order Quantity</th><th>Order Status</th><th>Order Date</th><th>User Name</th><th>Item Name</th><th>Item Price</th></tr>
        </thead>
    <tbody>
    <?php
        $limit = 5;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $sql = "SELECT o.oID, o.oQuantity, o.oStatus, o.oDate, u.uFname, u.uLname, i.itemName, i.itemPrice 
                FROM orders o
                JOIN users u ON o.uID = u.uID
                JOIN items i ON o.itemID = i.itemID
                ORDER BY o.oDate 
                LIMIT $limit OFFSET $offset";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['oID'] . '</td>';
            echo '<td>' . $row['oQuantity'] . '</td>';
            echo '<td>' . $row['oStatus'] . '</td>';
            echo '<td>' . $row['oDate'] . '</td>';
            echo '<td>' . $row['uFname'] . ' ' . $row['uLname'] . '</td>';
            echo '<td>' . $row['itemName'] . '</td>';
            echo '<td>' . $row['itemPrice'] . '</td>';
            echo '</tr>';
        }
    ?>
    </tbody>
    </table>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
           <li class="page-item <?php echo ($page <= 1 ? 'disabled' : ''); ?>">
              <a class="page-link" href="?page=<?php echo ($page - 1); ?>" tabindex="-1">Previous</a>
           </li>
           <li class="page-item">
              <a class="page-link" href="?page=<?php echo ($page + 1); ?>">Next</a>
           </li>
        </ul>
    </nav>

    <div class="mt-3">
        <a href="index.php" class="btn btn-danger">Logout</a>
        <a href="admin.php"  class="btn btn-primary">Back</a></br></br>
        <a href="pdf.php" target="_blank" class="btn btn-primary">Download PDF</a>
    </div>
</div>
</body>
</html>

<?php
$conn->close();
?>
