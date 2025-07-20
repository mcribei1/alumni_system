<?php
session_start();
if (!isset($_SESSION['UID'])) {
    header("Location: login.php");
    exit;
}
include 'config.php';

// Capture inputs
$headline = $_POST['headline'];
$description = $_POST['description']; // From CKEditor
$logoHTML = '<br><br><div style="text-align:center; margin-top:40px;">
    <img src="images/coles_logo.png" alt="Coles College of Business" style="height:70px;"><br>
    <p style="font-size:14px; color:#555;">Coles College of Business – Kennesaw State University</p>
</div>';
$description .= $logoHTML;
$link = !empty($_POST['link']) ? $_POST['link'] : null;
$recipients = $_POST['recipients'];
$date = date("Y-m-d");
$fileLoc = null;
$uploadSuccess = true;

// Handle File Upload
if (isset($_FILES['newsletter_file']) && $_FILES['newsletter_file']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $fileName = basename($_FILES['newsletter_file']['name']);
    $targetPath = $uploadDir . time() . "_" . $fileName;

    if (move_uploaded_file($_FILES['newsletter_file']['tmp_name'], $targetPath)) {
        $fileLoc = $targetPath;
    } else {
        $uploadSuccess = false;
    }
}

// Insert into newsletter table
$stmt = $conn->prepare("INSERT INTO newsletter (newsDate, headline, description, link, fileLoc) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $date, $headline, $description, $link, $fileLoc);
$stmt->execute();
$newsletterID = $stmt->insert_id;
$stmt->close();

// Insert into sentTo
if ($recipients && $uploadSuccess) {
    if (in_array("all", $recipients)) {
        $result = mysqli_query($conn, "SELECT alumniID FROM alumni");
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['alumniID'];
            mysqli_query($conn, "INSERT INTO sentTo (alumniID, NID, sentDate) VALUES ($id, $newsletterID, '$date')");
        }
    } else {
        foreach ($recipients as $id) {
            mysqli_query($conn, "INSERT INTO sentTo (alumniID, NID, sentDate) VALUES ($id, $newsletterID, '$date')");
        }
    }

    $msg = "✅ Newsletter successfully sent!";
} else {
    $msg = "❌ Failed to send newsletter. Please try again.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Newsletter Status</title>
    <link rel="stylesheet" href="css/newsletter.css">
</head>
<body>
<div class="confirmation-box">
    <h2>Newsletter Successfully Sent!</h2>
    
    <!-- Scrappy Image -->
    <img src="images/scrappy-logo.jpg" alt="Scrappy the Owl" style="height:100px; margin-top:20px;">
    
    <br><br>
    <a href="dashboard.php" class="btn-back">Return to Dashboard</a>
</div>

