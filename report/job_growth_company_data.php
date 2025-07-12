<?php
include "../config.php";

$sql = "
    SELECT e.company, COUNT(*) AS jobCount
    FROM employment e
    WHERE e.company IS NOT NULL AND e.company <> ''
    GROUP BY e.company
    ORDER BY jobCount DESC
    LIMIT 10
";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "label" => $row['company'],
        "value" => (int)$row['jobCount']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
