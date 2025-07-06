<?php
session_start();
include "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_POST["uid"];
    $password = $_POST["password"];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM user WHERE UID = ? AND password = ?");
    $stmt->bind_param("ss", $uid, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Login successful
    if ($result->num_rows == 1) {
        $_SESSION["UID"] = $uid;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | KSU Alumni Portal</title>
  <link rel="stylesheet" href="css/login.css" />
</head>
<body>
  <div class="background-wrap">
    <a href="index.php" class="back-link">← Back to Home</a>
    <div class="login-container">
      <img src="images/owl ksu logo.png" alt="KSU Logo" class="login-logo" />
      <h2>Login to Your Account</h2>

      <?php if ($error): ?>
        <div class="error" style="color:red; margin-bottom: 1em;"><?php echo $error; ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <input type="text" name="uid" placeholder="User ID" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">Login</button>
      </form>

      <p>Don’t have an account? <a href="signup.php">Sign Up</a></p>
    </div>
  </div>
</body>
</html>
