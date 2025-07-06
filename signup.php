<?php
session_start();
include "config.php";

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_POST["username"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check for existing UID
    $check = $conn->prepare("SELECT UID FROM user WHERE UID = ?");
    $check->bind_param("s", $uid);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "User ID already exists. Please choose a different one.";
    } else {
        // Insert user with default view-only privileges
        $stmt = $conn->prepare("INSERT INTO user (UID, password, fName, lName, viewPriviledgeYN, insertPriviledgeYN, updatePriviledgeYN, deletePriviledgeYN) VALUES (?, ?, ?, ?, 'Y', 'N', 'N', 'N')");
        $stmt->bind_param("ssss", $uid, $password, $fname, $lname);

        if ($stmt->execute()) {
            $success = "Account created successfully! You can now <a href='login.php'>login</a>.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }
    $check->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Create Account</title>
  <link rel="stylesheet" href="css/signup.css" />
</head>
<body>
  <div class="background-wrap">
    <a href="index.php" class="back-link">← Back to Home</a>
    <div class="signup-container">
      <h2>Create Your Account</h2>

      <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
      <?php endif; ?>

      <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
      <?php endif; ?>

      <form action="" method="POST">
        <input type="text" name="username" placeholder="Username" required />
        <input type="text" name="fname" placeholder="First Name" required />
        <input type="text" name="lname" placeholder="Last Name" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">Create Account</button>
      </form>

      <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
  </div>
</body>
</html>
