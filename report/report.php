<?php
session_start();
if (!isset($_SESSION["UID"])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports Overview</title>
    <link rel="stylesheet" href="../css/report.css">
</head>
<body>

<!-- Hero Header -->
<div class="hero-header">
  <div class="overlay">
    <div class="hero-title">Explore Reports & Insights</div>
  </div>
</div>

<!-- Report Tiles -->
<div class="container">
    <div class="report-grid">

        <!-- Directory Report -->
        <div class="report-box">
            <h3>📇 Alumni Directory</h3>
            <p><strong>Search and filter alumni</strong> by name, graduation year, or degree.</p>
            <div class="btn-container">
            <a href="directory.php" class="btn">View Report</a>
        </div>
</div>

        <!-- Donation Report -->
        <div class="report-box">
            <h3>💰 Donations Overview</h3>
            <p><strong>Visualize donation trends</strong> and average contribution history.</p>
             <div class="btn-container">
            <a href="donation_report.php" class="btn">View Report</a>
</div>
        </div>


        <!-- Skillset Report -->
        <div class="report-box">
            <h3>🧠 Alumni Skillsets</h3>
            <p><strong>Explore alumni skill distribution</strong> by category or frequency.</p>
             <div class="btn-container">
            <a href="skillset.php" class="btn">View Report</a>
        </div>
</div>

        <!-- Growth Report -->
        <div class="report-box">
            <h3>📈 Alumni Growth</h3>
            <p><strong>Track alumni registration</strong> by year and observe trends.</p>
            <a href="job_growth.php" class="btn">View Report</a>
        </div>

    </div>
</div>
    <div class="back-btn-wrapper">
        <a href="../dashboard.php" class="btn btn-back">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
