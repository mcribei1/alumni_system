<?php
include "../config.php";

$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? 'all';

$query = "
    SELECT a.alumniID, CONCAT(a.fName, ' ', a.lName) AS fullName,
           e.EID, e.jobTitle, e.company, e.startDate, e.endDate
    FROM alumni a
    LEFT JOIN employment e ON a.alumniID = e.alumniID
    WHERE (a.fName LIKE ? OR a.lName LIKE ?)
";

$like = "%$search%";

if ($status === 'current') {
    $query .= " AND (e.endDate IS NULL OR e.endDate = '')";
} elseif ($status === 'past') {
    $query .= " AND e.endDate IS NOT NULL AND e.endDate <> ''";
}

$query .= " ORDER BY a.alumniID, e.startDate";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$result = $stmt->get_result();

$employment = [];

while ($row = $result->fetch_assoc()) {
    $alumniID = $row['alumniID'];
    if (!isset($employment[$alumniID])) {
        $employment[$alumniID] = [
            'name' => $row['fullName'],
            'records' => []
        ];
    }
    $employment[$alumniID]['records'][] = [
        'title' => $row['jobTitle'],
        'company' => $row['company'],
        'start' => $row['startDate'],
        'end' => $row['endDate'] ?: 'Present'
    ];
}

// Output HTML
foreach ($employment as $id => $data) {
    $recordCount = count($data['records']);
    $lastRecord = end($data['records']);
    $lastEnd = $lastRecord['end'];

    echo "<div class='alumni-card'>";
    echo "<button class='alumni-toggle' data-target='details-$id'>";
    echo "<strong>{$data['name']}</strong><br>";
    echo "<span>{$recordCount} job" . ($recordCount > 1 ? "s" : "") . " – {$lastEnd}</span>";
    echo "</button>";

    echo "<div class='job-details' id='details-$id' style='display:none; padding-top: 10px;'>";

    foreach ($data['records'] as $record) {
    $start = date("M Y", strtotime($record['start']));
    $end = $record['end'] ?: 'Present';

    // Check for company name
    $companyDisplay = !empty($record['company'])
        ? htmlspecialchars($record['company'])
        : "<span class='missing-company'>No company listed</span>";

    echo "<div class='job-entry'>";
    echo "<strong>" . htmlspecialchars($record['title']) . "</strong><br>";
    echo "$companyDisplay<br>";
    echo "<small>{$start} – {$end}</small>";
    echo "</div><br>";
}


    echo "</div></div>";
}

$conn->close();
?>
