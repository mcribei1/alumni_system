<?php
include "../config.php";

$name = $_GET['name'] ?? '';
$type = $_GET['type'] ?? 'all';
$amount = $_GET['amount'] ?? 'all';
$year = $_GET['year'] ?? '';

// Base SQL
$sql = "
    SELECT 
        a.alumniID,
        CONCAT(a.fName, ' ', a.lName) AS fullName,
        COUNT(d.donationID) AS donationCount,
        SUM(d.donationAmt) AS totalAmount,
        MAX(d.donationDT) AS lastDonationDate
    FROM alumni a
    JOIN donations d ON a.alumniID = d.alumniID
    WHERE 1
";

// Filtering logic
if (!empty($name)) {
    $sql .= " AND (a.fName LIKE '%$name%' OR a.lName LIKE '%$name%')";
}
if ($type !== 'all') {
    $sql .= " AND d.reason = '$type'";
}
if ($amount === 'low') {
    $sql .= " AND d.donationAmt < 250";
} elseif ($amount === 'mid') {
    $sql .= " AND d.donationAmt BETWEEN 250 AND 500";
} elseif ($amount === 'high') {
    $sql .= " AND d.donationAmt > 500";
}
if (!empty($year)) {
    $sql .= " AND DATE_FORMAT(d.donationDT, '%Y-%m') = '$year'";
}

$sql .= " GROUP BY a.alumniID ORDER BY totalAmount DESC LIMIT 50";

$result = $conn->query($sql);

// Render HTML results
while ($row = $result->fetch_assoc()) {
    $alumniID = $row['alumniID'];
    $fullName = $row['fullName'];
    $totalAmount = number_format($row['totalAmount'], 2);
    $lastDate = $row['lastDonationDate'];
    $count = $row['donationCount'];

    echo "
        <div class='alumni-card'>
            <button class='alumni-toggle' data-target='donation-details-$alumniID'>
                <strong>$fullName</strong><br>
                \$$totalAmount — $lastDate | $count donation" . ($count > 1 ? 's' : '') . "
            </button>
            <div id='donation-details-$alumniID' class='donation-detail' style='display: none;'>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Campaign</th>
                        </tr>
                    </thead>
                    <tbody>
    ";

    // Detailed donation rows
    $donationSQL = "
        SELECT donationDT, donationAmt, reason, description 
        FROM donations 
        WHERE alumniID = $alumniID 
        ORDER BY donationDT DESC
    ";
    $donations = $conn->query($donationSQL);

    while ($donation = $donations->fetch_assoc()) {
        $dt = $donation['donationDT'];
        $amt = number_format($donation['donationAmt'], 2);
        $reason = $donation['reason'];
        $desc = $donation['description'];

        echo "
            <tr>
                <td>$dt</td>
                <td>\$$amt</td>
                <td>$reason</td>
                <td>$desc</td>
            </tr>
        ";
    }

    echo "
                    </tbody>
                </table>
            </div>
        </div>
    ";
}
?>

?>

