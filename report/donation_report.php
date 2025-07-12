<?php
session_start();
if (!isset($_SESSION['UID'])) {
  header('Location: ../login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Alumni Donation Report</title>
  <link rel="stylesheet" href="../css/donation_report.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="logo-header">
  <img src="../images/coles_logo.png" class="logo" alt="KSU Logo">
  <h1>Alumni Donation Report</h1>
</div>

<!-- Filter + Chart Controls -->
<div class="controls">
  <input type="text" id="searchInput" placeholder="Search by name">

  <select id="donationType">
    <option value="all">All Types</option>
    <option value="Recurring">Recurring</option>
    <option value="One-Time">One-Time</option>
  </select>

  <select id="amountRange">
    <option value="all">All Amounts</option>
    <option value="low">&lt; $250</option>
    <option value="mid">$250–$500</option>
    <option value="high">&gt; $500</option>
  </select>

  <input type="month" id="donationYear">
  <button id="applyBtn" class="btn">Apply Filter</button>
  <button id="generateChartBtn" class="btn btn-yellow">📊 Generate Report</button>
</div>

<!-- Chart Area -->
<div class="chart-section">
  <canvas id="donationChart" style="max-width: 700px; margin-top: 20px;"></canvas>
</div>

<!-- Donation Cards -->
<div id="results" class="results-container"></div>

<script src="../js/donation_report.js"></script>
</body>
</html>

