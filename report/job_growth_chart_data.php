<?php
include "../config.php";

$type = $_GET['type'] ?? 'month';
$data = [];

switch ($type) {
  case 'month':
    $sql = "SELECT DATE_FORMAT(date, '%Y-%m') AS label, SUM(amount) AS total FROM donations GROUP BY label ORDER BY label";
    break;

  case 'type':
    $sql = "SELECT type AS label, SUM(amount) AS total FROM donations GROUP BY type ORDER BY total DESC";
    break;

  case 'amount':
    $sql = "SELECT CONCAT(a.fName, ' ', a.lName) AS label, SUM(d.amount) AS total 
            FROM donations d 
            JOIN alumni a ON d.alumniID = a.alumniID 
            GROUP BY d.alumniID 
            ORDER BY total DESC 
            LIMIT 10";
    break;

  default:
    echo json_encode([]);
    exit;
}

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $data[] = [
    "label" => $row['label'],
    "total" => (float)$row['total']
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>

