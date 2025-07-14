<?php
require_once '../config.php'; // Make sure this path points to your DB config
header('Content-Type: application/json');

// Get the chart type from the URL (alumni, company, or state)
$type = $_GET['type'] ?? 'alumni';

switch ($type) {
    case 'company':
        // Count jobs by company
        $sql = "SELECT company AS name, COUNT(*) AS count 
                FROM employment 
                GROUP BY company 
                ORDER BY count DESC 
                LIMIT 10";
        break;

    case 'state':
        // Count jobs by state
        $sql = "SELECT state AS name, COUNT(*) AS count 
                FROM employment 
                GROUP BY state 
                ORDER BY count DESC 
                LIMIT 10";
        break;

    case 'alumni':
    default:
        // Count jobs per alumni (full name)
        $sql = "SELECT CONCAT(a.fName, ' ', a.lName) AS name, COUNT(e.EID) AS count 
                FROM alumni a
                JOIN employment e ON a.alumniID = e.alumniID 
                GROUP BY a.alumniID 
                ORDER BY count DESC 
                LIMIT 10";
        break;
}

// Execute query
$result = $conn->query($sql);

// Format output
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'name' => $row['name'],
        'count' => (int)$row['count']
    ];
}

// Output JSON
echo json_encode($data);
?>


