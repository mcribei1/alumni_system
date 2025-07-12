<?php
session_start();
if (!isset($_SESSION["UID"])) {
    header("Location: login.php");
    exit();
}
include "config.php";

// Fetch Quick Stats
$alumniCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM alumni"))['total'];
$newsletterCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM newsletter"))['total'];
$donationSum = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(donationAmt) AS total FROM donations WHERE YEAR(donationDT) = YEAR(CURDATE())"))['total'] ?? 0;
$donationAvg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(donationAmt) AS avg FROM donations"))['avg'] ?? 0;
$newAlumniThisMonth = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM alumni WHERE MONTH(DOB) = MONTH(CURDATE()) AND YEAR(DOB) = YEAR(CURDATE())"))['count'];

// Activity Feed (latest 10 combined)
$activityFeed = [];

$feedQueries = [
    "SELECT newsDate AS date, CONCAT('Newsletter created: ', headline) AS activity FROM newsletter",
    "SELECT donationDT AS date, CONCAT('Donation: $', donationAmt, ' from Alumni ID ', alumniID) AS activity FROM donations",
    "SELECT DOB AS date, CONCAT('Alumni added: ', fName, ' ', lName) AS activity FROM alumni"
];

foreach ($feedQueries as $query) {
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $activityFeed[] = $row;
    }
}

// Sort all entries by date descending
usort($activityFeed, function ($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

$activityFeed = array_slice($activityFeed, 0, 10);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>KSU Alumni Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/dashboard.css" />
</head>
<body>

  <div class="hero-header">
    <div class="overlay">
      <a href="logout.php" class="logout-btn top-right">Logout</a>
      <div class="hero-content">
        <img src="images/owl ksu logo.png" alt="KSU Logo" class="hero-logo" />
        <h1>Welcome, <?php echo $_SESSION["UID"] ?>!</h1>
      </div>
    </div>
  </div>

  <div class="action-buttons">
    <a href="newsletter.php" class="btn">📬 Create Newsletter</a>
   <a href="report/report.php" class="btn">📊 View Reports</a>

  </div>

  <div class="stats-container">
    <div class="stat-card"><h3>Total Alumni</h3><p><?php echo $alumniCount; ?></p></div>
    <div class="stat-card"><h3>Newsletters Sent</h3><p><?php echo $newsletterCount; ?></p></div>
    <div class="stat-card"><h3>Donations This Year</h3><p>$<?php echo number_format($donationSum, 2); ?></p></div>
    <div class="stat-card"><h3>Average Donation</h3><p>$<?php echo number_format($donationAvg, 2); ?></p></div>
    <div class="stat-card"><h3>New Alumni This Month</h3><p><?php echo $newAlumniThisMonth; ?></p></div>
  </div>

  <div class="activity-feed">
    <h2>Recent Activity</h2>
    <ul>
      <?php foreach ($activityFeed as $entry): ?>
        <li><span class="date">[<?php echo date("M d, Y", strtotime($entry['date'])); ?>]</span> <?php echo $entry['activity']; ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<!-- Profile Menu -->
<div class="profile-container">
  <img src="images/user_placeholder.png" alt="Profile" class="profile-icon" id="profileIcon">

  <div class="profile-menu" id="profileMenu">
    <p><strong><?php echo $_SESSION['email']; ?></strong></p>
    <a href="#">⚙️ Account Settings</a>
    <a href="logout.php">🔒 Logout</a>
  </div>
</div>
<script>
  const profileIcon = document.getElementById("profileIcon");
  const profileMenu = document.getElementById("profileMenu");

  profileIcon.addEventListener("click", () => {
    profileMenu.style.display = profileMenu.style.display === "block" ? "none" : "block";
  });

  document.addEventListener("click", function (e) {
    if (!profileIcon.contains(e.target) && !profileMenu.contains(e.target)) {
      profileMenu.style.display = "none";
    }
  });
</script>

</body>
</html>
