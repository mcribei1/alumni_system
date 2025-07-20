<?php
include '../config.php';

$clickedSkill = $_GET['skill'] ?? '';

// Get top N skills
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
$highlightIndex = -1;

$index = 0;
while ($row = $result->fetch_assoc()) {
  $labels[] = $row['skill'];
  $counts[] = (int)$row['count'];

  if (strtolower($row['skill']) === strtolower($clickedSkill)) {
    $highlightIndex = $index;
  }

  $index++;
}

header('Content-Type: application/json');
echo json_encode([
  'labels' => $labels,
  'counts' => $counts,
  'highlightIndex' => $highlightIndex
]);
?>


