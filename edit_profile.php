<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['uContact'])) {
    header("Location: index.php");
    exit();
}

// Connect to database
require 'conx.php';

// Fetch user profile
$uContact = $_SESSION['uContact'];
$sql = "SELECT * FROM users WHERE uContact = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $uContact);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    echo "Error: User not found.";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uFname = $_POST['uFname'];
    $uLname = $_POST['uLname'];
    $uContact = $_POST['uContact'];
    $profilePic = $user['uProfilePic'];

    // Handle profile picture upload
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['profilePic']['name']);
        if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $uploadFile)) {
            $profilePic = basename($_FILES['profilePic']['name']);
        }
    }

    // Update user profile
    $sql = "UPDATE users SET uFname = ?, uLname = ?, uContact = ?, uProfilePic = ? WHERE uID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $uFname, $uLname, $uContact, $profilePic, $user['uID']);
    $stmt->execute();

    // Redirect to profile page
    header("Location: profile.php");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beyond D' Crust - profile</title>

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
            <h1 class="text-center mb-4">Edit Profile</h1>
            <div class="card">
                <div class="card-body">
                    <form action="edit_profile.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="uFname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="uFname" name="uFname" value="<?php echo $user['uFname']; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="uLname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="uLname" name="uLname" value="<?php echo $user['uLname']; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="uContact" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="uContact" name="uContact" value="<?php echo $user['uContact']; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="profilePic" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="profilePic" name="profilePic">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
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