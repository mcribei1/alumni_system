<?php
include "../config.php";

$name = $_GET['name'] ?? '';
$type = $_GET['type'] ?? 'all';
$amount = $_GET['amount'] ?? 'all';
$year = $_GET['year'] ?? '';

// Base query
$sql = "
  SELECT CONCAT(a.fName, ' ', a.lName) AS label, SUM(d.donationAmt) AS total
  FROM alumni a
  JOIN donations d ON a.alumniID = d.alumniID
  WHERE 1
";

// Filters
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

$sql .= " GROUP BY a.alumniID ORDER BY total DESC LIMIT 20";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
  $data[] = [
    'label' => $row['label'],
    'total' => floatval($row['total'])
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
