<?php
session_start();

// Redirect to login if not logged in or not an admin
if (!isset($_SESSION['uContact']) || $_SESSION['uType'] != 1) {
    header("Location: index.php");
    exit();
}

// Connect to database
require 'conx.php';

// Fetch audit trail data with user details
$sql = "SELECT audit_trail.timestamp, audit_trail.action, users.uFname, users.uLname
        FROM audit_trail
        INNER JOIN users ON audit_trail.user = users.uContact
        ORDER BY audit_trail.timestamp DESC";

$result = $conn->query($sql);

if (!$result) {
    throw new mysqli_sql_exception("Database query failed: " . $conn->error);
}

// Check if PDF download requested
if (isset($_GET['download_pdf'])) {
    require_once('tcpdf/tcpdf.php');

    // Create new TCPDF object
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Audit Trail PDF');
    $pdf->SetSubject('Audit Trail');
    $pdf->SetKeywords('Audit, Trail, PDF');

    // Set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // Set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // Set font
    $pdf->SetFont('helvetica', '', 12);

    // Add a page
    $pdf->AddPage();

    // Initialize HTML content for PDF
    $html = '<h1>Audit Trail Report</h1>';
    $html .= '<p>Report generated on ' . date('Y-m-d') . '</p>';

    // Start table
    $html .= '<table border="1">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';

    // Loop through query results and add rows to HTML table
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['timestamp'] . '</td>';
        $html .= '<td>' . $row['uFname'] . ' ' . $row['uLname'] . '</td>';
        $html .= '<td>' . $row['action'] . '</td>';
        $html .= '</tr>';
    }

    // Close table
    $html .= '</tbody></table>';

    // Write HTML content to PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Close and output PDF document
    $pdf->Output('audit_trail_report.pdf', 'D');

    // Close database connection and exit
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beyond D' Crust - Audit Trail</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
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
            justify-content: space-between;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #7f0001;
            border-bottom: 3px solid #fddf07;
            color: white;
            padding: 10px 0;
            position: relative;
            z-index: 2;
        }
        .logo {
            color: white;
            font-weight: 600;
            font-size: 2rem;
            text-decoration: none;
            margin-left: 20px;
        }
        .navbar-toggler-icon {
            color: white;
        }
        .navbar-nav {
            margin-right: 20px;
        }
        .nav-link {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            margin-top: 10px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 15px rgba(0, 0, 0, 25);
        }
        .nav-link:hover {
            background-color: #fddf07;
            color: white;
            text-decoration: none;
        }
        .content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            
            margin: 100px;
            padding: 100px;
            
            border-radius: 10px;
        }
        .container {
            width: 80%;
            padding: 50px;
            border-radius: 10px;
            background-color: rgba(102, 0, 1, 0.8);
            border: 2px solid #fddf07;
        }
        .container h1 {
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            margin-top: 20px;
            background-color: white; /* Background color for the table */
            border-radius: 10px;
            overflow: hidden; /* Ensures the table stays within its container */
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd; /* Border between rows */
        }
        th {
            background-color: #f0f0f0; /* Header background color */
            color: #7f0001; /* Header text color */
            font-weight: bold; /* Bold font for headers */
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Even row background color */
        }
        tr:hover {
            background-color: #f1f1f1; /* Hover row background color */
        }
        .btn-primary {
            background-color: #fddf07;
            border-color: #7f0001;
            text-shadow: none;
        }
        .btn-primary:hover {
            background-color: #fddf07;
            border-color: #fddf07;
            text-shadow: none;
        }
        .footer {
            background-color: #7f0001;
            border-top: 3px solid #fddf07;
            color: white;
            padding: 10px 0;
            text-align: center;
            position: relative;
            z-index: 2;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand logo" href="#">BEYOND THE CRUST</a>
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
    <div class="content">

        <div class="container">
            <h1 class="text-center mb-4">Audit Trail</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['timestamp']; ?></td>
                            <td><?php echo $row['uFname'] . ' ' . $row['uLname']; ?></td>
                            <td><?php echo $row['action']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Button to download PDF -->
            <div class="text-center mt-4">
                <a href="?download_pdf=1" class="btn btn-primary">Download Audit Trail as PDF</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="text-center p-3">
            &copy; 2024 Beyond the Crust
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
