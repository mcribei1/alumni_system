<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipients = htmlspecialchars($_POST['recipients']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = $_POST['message']; // allow HTML content

    echo "<h2>Newsletter Sent</h2>";
    echo "<p><strong>To:</strong> $recipients</p>";
    echo "<p><strong>Subject:</strong> $subject</p>";
    echo "<p><strong>Message:</strong></p>";
    echo "<div style='border:1px solid #ccc; padding:10px;'>$message</div>";
}

#$recipients = $_POST['recipients'] ?? [];

#echo "<h2>Newsletter Sent</h2>";
#echo "<p><strong>To:</strong> " . implode(', ', array_map('htmlspecialchars', $recipients)) . "</p>";


?>
