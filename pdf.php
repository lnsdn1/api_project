<?php
require_once('tcpdf/tcpdf.php');
require('conx.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Sales Report PDF');
$pdf->SetSubject('Sales Report');
$pdf->SetKeywords('Sales, Report, PDF');

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->SetFont('helvetica', '', 12);

$pdf->AddPage();

$sql = "SELECT o.oID, o.oQuantity, o.oStatus, o.oDate, u.uFname, u.uLname, i.itemName, i.itemPrice 
        FROM orders o
        JOIN users u ON o.uID = u.uID
        JOIN items i ON o.itemID = i.itemID
        ORDER BY o.oDate";
$result = $conn->query($sql);

$html = '<h1>Sales Report</h1>';
$html .= '<p>Report generated on ' . date('Y-m-d') . '</p>';
$html .= '<table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Quantity</th>
                    <th>Order Status</th>
                    <th>Order Date</th>
                    <th>User Name</th>
                    <th>Item Name</th>
                    <th>Item Price</th>
                </tr>
            </thead>
            <tbody>';
while ($row = $result->fetch_assoc()) {
    $html .= '<tr>';
    $html .= '<td>' . $row['oID'] . '</td>';
    $html .= '<td>' . $row['oQuantity'] . '</td>';
    $html .= '<td>' . $row['oStatus'] . '</td>';
    $html .= '<td>' . $row['oDate'] . '</td>';
    $html .= '<td>' . $row['uFname'] . ' ' . $row['uLname'] . '</td>';
    $html .= '<td>' . $row['itemName'] . '</td>';
    $html .= '<td>' . $row['itemPrice'] . '</td>';
    $html .= '</tr>';
}
$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('sales_report.pdf', 'D');

$conn->close();
?>
