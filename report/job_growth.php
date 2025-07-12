

<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Alumni Job Growth Report</title>
  <link rel="stylesheet" href="../css/job_growth.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js for the bar chart -->
</head>
<body>

<div class="header">
  <img src="../images/coles_logo.png" class="logo" alt="KSU Logo">
  <h1>Alumni Job Growth Report</h1>
</div>

<div class="controls">
  <input type="text" id="searchInput" placeholder="Search by name">
  <select id="statusFilter">
    <option value="all">All</option>
    <option value="current">Currently Working</option>
    <option value="past">Past Jobs Only</option>
  </select>
  <button id="applyBtn" class="btn">Apply Filter</button>
</div>

<!-- Chart Button and Canvas -->
<!-- Chart Controls -->
<div class="chart-center">
  <div class="chart-controls">
    <select id="chartType" class="btn">
      <option value="alumni">👤 Jobs by Alumni</option>
      <option value="company">🏢 Jobs by Company</option>
      <option value="state">📍 Jobs by State</option>
    </select>
    <button id="generateChartBtn" class="btn btn-yellow">📊 Generate Report</button>
  </div>
  <canvas id="growthChart" style="max-width: 700px; margin-top: 20px;"></canvas>
</div>


<!-- Where alumni job cards will appear -->
<div id="results" class="results-container">
  <!-- Filled by JS (job_growth_data.php) -->
</div>

<script src="../js/job_growth_chart.js"></script>
</body>
</html>
