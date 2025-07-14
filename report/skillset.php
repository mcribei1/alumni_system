<!-- ✅ FINAL VERSION: skillset.php -->
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Skill Set Report</title>
  <link rel="stylesheet" href="../css/skillset.css?v=2">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div style="padding-left: 1rem; margin-top: 1rem;">
  <a href="report.php" class="btn-back">Back to Reports</a>
</div>
  <div class="header">
    <img src="../images/coles_logo.png" class="logo" alt="KSU Logo">
    <h1>Alumni Skillset Report</h1>
  </div>

  <div class="controls">
    <input type="text" id="skillKeyword" placeholder="Search skill...">
    <select id="stateFilter">
      <option value="">All States</option>
    </select>
    <select id="jobTitleFilter">
      <option value="">All Job Titles</option>
    </select>
    <button id="applySkillFilter">Apply Filter</button>
    <button id="generateSkillsetReport" class="btn btn-yellow">📊 View Top Skills</button>
  </div>

  <div id="skillsetResults" class="results-container"></div>

  <script src="../js/skillset.js"></script>
</body>
</html>

