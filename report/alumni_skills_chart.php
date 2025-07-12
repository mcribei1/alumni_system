<?php
include "../config.php";

$alumniID = $_GET['id'] ?? '';
$clickedSkill = $_GET['skill'] ?? '';

if (!$alumniID || !$clickedSkill) {
  echo json_encode(['error' => 'Missing parameters']);
  exit;
}

$sql = "SELECT skill FROM skillset WHERE alumniID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $alumniID);
$stmt->execute();
$result = $stmt->get_result();

$skills = [];
while ($row = $result->fetch_assoc()) {
  $skills[] = $row['skill'];
}

$labels = $skills;
$counts = array_fill(0, count($skills), 1); // Each skill shown once
$highlightIndex = array_search($clickedSkill, $skills);

echo json_encode([
  'labels' => $labels,
  'counts' => $counts,
  'highlightIndex' => $highlightIndex
]);
?>
