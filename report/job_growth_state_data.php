<?php
include "../config.php";

$sql = "
    SELECT e.state, COUNT(*) AS jobCount
    FROM employment e
    WHERE e.state IS NOT NULL AND e.state <> ''
    GROUP BY e.state
    ORDER BY jobCount DESC
    LIMIT 10
";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "label" => $row['state'],
        "value" => (int)$row['jobCount']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
