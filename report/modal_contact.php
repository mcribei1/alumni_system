<?php
session_start();
if (!isset($_SESSION["UID"])) {
    echo "Access denied.";
    exit();
}

if (!isset($_GET['id'])) {
    echo "No alumni ID provided.";
    exit();
}

include "../config.php";
$alumniID = intval($_GET['id']);

// Query contact info
$stmt = $conn->prepare("SELECT fName, lName, email, phone FROM alumni WHERE alumniID = ?");
$stmt->bind_param("i", $alumniID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "Alumni not found.";
    exit();
}
$alumni = $result->fetch_assoc();

// Query primary address
$addrStmt = $conn->prepare("SELECT city, state, zipCode FROM address WHERE alumniID = ? AND primaryYN = 'Y' LIMIT 1");
$addrStmt->bind_param("i", $alumniID);
$addrStmt->execute();
$address = $addrStmt->get_result()->fetch_assoc();
?>

<div class="px-2">
  <p><strong>Name:</strong> <?= htmlspecialchars($alumni['fName'] . ' ' . $alumni['lName']) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($alumni['email']) ?></p>
  <p><strong>Phone:</strong> <?= htmlspecialchars($alumni['phone']) ?></p>
  <?php if ($address): ?>
    <p><strong>Address:</strong> <?= htmlspecialchars($address['city']) ?>, <?= htmlspecialchars($address['state']) ?> <?= htmlspecialchars($address['zipCode']) ?></p>
  <?php else: ?>
    <p><em>No primary address on file.</em></p>
  <?php endif; ?>
</div>
<a href="../newsletter.php?to=<?= urlencode($alumni['email']) ?>" class="btn btn-outline-warning mt-3">✉️ Create Newsletter</a>
