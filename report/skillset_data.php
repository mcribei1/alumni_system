<?php
include "../config.php";

$skillKeyword = $_GET['skill'] ?? '';
$state = $_GET['state'] ?? '';
$jobTitle = $_GET['jobTitle'] ?? '';

$sql = "
  SELECT 
    a.alumniID,
    CONCAT(a.fName, ' ', a.lName) AS fullName,
    s.skill,
    e.jobTitle,
    ad.state
  FROM alumni a
  JOIN skillset s ON a.alumniID = s.alumniID
  LEFT JOIN employment e ON a.alumniID = e.alumniID
  LEFT JOIN address ad ON a.alumniID = ad.alumniID
  WHERE 1
";

if (!empty($skillKeyword)) {
  $sql .= " AND s.skill LIKE '%$skillKeyword%'";
}
if (!empty($state)) {
  $sql .= " AND ad.state = '$state'";
}
if (!empty($jobTitle)) {
  $sql .= " AND e.jobTitle = '$jobTitle'";
}

$sql .= " ORDER BY a.lName ASC LIMIT 100";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<table class='skillset-table'>
          <thead>
            <tr>
              <th>Name</th>
              <th>Skill</th>
              <th>Job Title</th>
              <th>State</th>
            </tr>
          </thead>
          <tbody>";

  while ($row = $result->fetch_assoc()) {
    $name = htmlspecialchars($row['fullName']);
    $skill = htmlspecialchars($row['skill']);
    $jobTitle = htmlspecialchars($row['jobTitle'] ?? 'N/A');
    $state = htmlspecialchars($row['state'] ?? 'N/A');
    $alumniID = (int)$row['alumniID'];

    echo "
      <tr>
        <td><button class='name-btn' data-id='$alumniID'>$name</button></td>
        <td><button class='skill-btn' data-skill='$skill' data-id='{$row['alumniID']}'>$skill</button></td>
        <td>$jobTitle</td>
        <td>$state</td>
      </tr>";
  }

  echo "</tbody></table>";
} else {
  echo "<p>No results found.</p>";
}
?>



