<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['uContact'])) {
    header("Location: index.php");
    exit();
}

// Connect to database
require 'conx.php';

// Fetch payments
$uContact = $_SESSION['uContact'];
$sql = "SELECT * FROM orders WHERE uContact = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $uContact);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beyond D' Crust - transactions</title>

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
        .content {
            flex: 1;
            display: flex;
            align-items: center; /* Vertical center */
            justify-content: center; /* Horizontal center */
        }
        .content h1 {
            color: white;
        }
        .container {
            background-color: rgba(102, 0, 1, 0.6); /* White background with some transparency */
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #fddf07;
            width: 80%; /* Adjust width as necessary */
            max-width: 1200px; /* Maximum width */
            overflow-y: scroll; /* Enable vertical scrolling */
            max-height: 70vh; /* Adjust height as necessary */
            scrollbar-width: none; /* For Firefox */
            -ms-overflow-style: none; /* For Internet Explorer and Edge */
            margin-bottom: 100px;
        }
        .container::-webkit-scrollbar {
            display: none; /* For Chrome, Safari, and Opera */
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
            <h1 class="text-center mb-4">Payment History</h1>
            <div class="card">
                <div class="card-body">
                    <center><h5 class="card-title">Your Payments</h5></center>
                    <?php if ($result->num_rows > 0): ?>
                        <ul class="list-group mb-4">
                            <?php while($row = $result->fetch_assoc()): ?>
                                <li class="list-group-item">
                                    <b><h6>Order ID:</b> <?php echo htmlspecialchars($row['id']); ?></h6>
                                    <b><p>Total Amount:</b> <?php echo htmlspecialchars($row['total_amount']); ?></p>
                                    <b><p>Reference Number:</b> <?php echo htmlspecialchars($row['reference_number']); ?></p>
                                    <b><p>Payment Method:</b> <?php echo htmlspecialchars($row['payment_method']); ?></p>
                                    <b><p>Order Date:</b> <?php echo isset($row['created_at']) ? htmlspecialchars($row['created_at']) : 'N/A'; ?></p>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>No payments found.</p>
                    <?php endif; ?>
                </div>
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
