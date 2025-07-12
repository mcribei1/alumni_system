<?php
include "../config.php";

$sql = "
  SELECT skill, COUNT(*) AS count
  FROM skillset
  GROUP BY skill
  ORDER BY count DESC
  LIMIT 10
";

$result = $conn->query($sql);
$labels = [];
$counts = [];

while ($row = $result->fetch_assoc()) {
  $labels[] = $row['skill'];
  $counts[] = (int)$row['count'];
}

echo json_encode([
  'labels' => $labels,
  'counts' => $counts
]);
?>
