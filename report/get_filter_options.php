<?php
include "../config.php";

$states = [];
$jobTitles = [];

// Get states
$stateQuery = $conn->query("SELECT DISTINCT state FROM address WHERE state IS NOT NULL AND state != '' ORDER BY state");
while ($row = $stateQuery->fetch_assoc()) {
  $states[] = $row['state'];
}

// Get job titles
$titleQuery = $conn->query("SELECT DISTINCT jobTitle FROM employment WHERE jobTitle IS NOT NULL AND jobTitle != '' ORDER BY jobTitle");
while ($row = $titleQuery->fetch_assoc()) {
  $jobTitles[] = $row['jobTitle'];
}

header('Content-Type: application/json');
echo json_encode([
  'states' => $states,
  'jobTitles' => $jobTitles
]);

