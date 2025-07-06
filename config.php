<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "alumni_db"; // ✅ Replace this if your database has a different name

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
