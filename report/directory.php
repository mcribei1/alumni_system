<?php
session_start();
if (!isset($_SESSION["UID"])) {
    header("Location: ../login.php");
    exit();
}
include "../config.php";

$majors = mysqli_query($conn, "SELECT DISTINCT major FROM degree ORDER BY major");
$minors = mysqli_query($conn, "SELECT DISTINCT minor FROM degree WHERE minor IS NOT NULL AND minor != '' ORDER BY minor");
$years = mysqli_query($conn, "SELECT DISTINCT YEAR(graduationDT) AS year FROM degree ORDER BY year DESC");

$filter_major = $_GET['major'] ?? '';
$filter_minor = $_GET['minor'] ?? '';
$filter_year = $_GET['year'] ?? '';
$sort_by = $_GET['sort'] ?? 'az';

$query = "
  SELECT a.alumniID, a.fName, a.lName, d.major, d.minor, d.graduationDT, e.jobTitle
  FROM alumni a
  JOIN degree d ON a.alumniID = d.alumniID
  JOIN employment e ON a.alumniID = e.alumniID
  WHERE e.currentYN = 'Y'
";

if ($filter_major !== '') {
    $query .= " AND d.major = '" . mysqli_real_escape_string($conn, $filter_major) . "'";
}
if ($filter_minor !== '') {
    $query .= " AND d.minor = '" . mysqli_real_escape_string($conn, $filter_minor) . "'";
}
if ($filter_year !== '') {
    $query .= " AND YEAR(d.graduationDT) = '" . mysqli_real_escape_string($conn, $filter_year) . "'";
}

switch ($sort_by) {
    case 'za':
        $query .= " ORDER BY a.lName DESC";
        break;
    case 'oldest':
        $query .= " ORDER BY d.graduationDT ASC";
        break;
    case 'newest':
        $query .= " ORDER BY d.graduationDT DESC";
        break;
    default:
        $query .= " ORDER BY a.lName ASC";
}

$results = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Alumni Directory</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/directory.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <div class="text-center mb-3">
  <img src="../images/coles_logo.png" alt="KSU Logo" style="height: 80px;">
</div>

  <h2 class="text-center mb-4">Alumni Directory</h2>

  <form method="GET" class="row g-3 align-items-center mb-4">
    <div class="col-auto">
      <select name="major" class="form-select">
        <option value="">All Majors</option>
        <?php while ($m = mysqli_fetch_assoc($majors)) {
          $selected = ($filter_major === $m['major']) ? 'selected' : '';
          echo "<option value='{$m['major']}' $selected>{$m['major']}</option>";
        } ?>
      </select>
    </div>
    <div class="col-auto">
      <select name="minor" class="form-select">
        <option value="">All Minors</option>
        <?php while ($min = mysqli_fetch_assoc($minors)) {
          $selected = ($filter_minor === $min['minor']) ? 'selected' : '';
          echo "<option value='{$min['minor']}' $selected>{$min['minor']}</option>";
        } ?>
      </select>
    </div>
    <div class="col-auto">
      <select name="year" class="form-select">
        <option value="">All Years</option>
        <?php while ($y = mysqli_fetch_assoc($years)) {
          $selected = ($filter_year === $y['year']) ? 'selected' : '';
          echo "<option value='{$y['year']}' $selected>{$y['year']}</option>";
        } ?>
      </select>
    </div>
    <div class="col-auto">
      <select name="sort" class="form-select">
        <option value="az" <?= $sort_by === 'az' ? 'selected' : '' ?>>A-Z</option>
        <option value="za" <?= $sort_by === 'za' ? 'selected' : '' ?>>Z-A</option>
        <option value="oldest" <?= $sort_by === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
        <option value="newest" <?= $sort_by === 'newest' ? 'selected' : '' ?>>Newest First</option>
      </select>
    </div>
    <div class="col-auto">
      <button type="submit" class="btn ksu-yellow">Apply Filters</button>
    </div>
  </form>

  <table class="table table-bordered table-hover">
    <thead class="table-warning">
      <tr>
        <th>Name</th>
        <th>Major</th>
        <th>Minor</th>
        <th>Graduation Year</th>
        <th>Current Job Title</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($results)) { ?>
        <tr data-bs-toggle="modal" data-bs-target="#contactModal" data-id="<?= $row['alumniID'] ?>">
          <td><?= htmlspecialchars($row['fName'] . ' ' . $row['lName']) ?></td>
          <td><?= htmlspecialchars($row['major']) ?></td>
          <td><?= htmlspecialchars($row['minor']) ?></td>
          <td><?= htmlspecialchars(date('Y', strtotime($row['graduationDT']))) ?></td>
          <td><?= htmlspecialchars($row['jobTitle']) ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="contactModalLabel">Alumni Contact Info</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalContent">
        Loading...
      </div>
    </div>
  </div>
</div>

<script>
  const modal = document.getElementById('contactModal');
  modal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const alumniID = button.getAttribute('data-id');
    fetch(`modal_contact.php?id=${alumniID}`)
      .then(response => response.text())
      .then(data => document.getElementById('modalContent').innerHTML = data);
  });
</script>
</body>
</html>
