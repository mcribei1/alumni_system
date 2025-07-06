<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>KSU Alumni Portal</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <header>
    <div class="logo">
      <img src="images/owl ksu logo.png" alt="KSU Logo">
    </div>
    <nav>
  <ul>
    <li><a href="https://www.kennesaw.edu/alumni/events-programs/index.php" target="_blank">Event</a></li>
    <li><a href="login.php">Login</a></li>
    <li><a href="signup.php">Sign Up</a></li>
  </ul>
  <div class="hamburger" onclick="document.querySelector('nav ul').classList.toggle('show')">
    <span></span>
    <span></span>
    <span></span>
  </div>
</nav>
  </header>

  <section class="hero">
    <div class="hero-text">
      <h1>Welcome to the KSU Alumni Portal</h1>
      <p><h2>Reconnect, stay informed, and explore alumni impact across the world.<h2></p>
      <a href="login.php" class="btn">Login</a>
      <a href="signup.php" class="btn btn-secondary">Create Account</a>
    </div>
  </section>

  <script>
    // Responsive menu toggle (optional)
    const hamburger = document.querySelector(".hamburger");
    hamburger?.addEventListener("click", () => {
      document.querySelector("nav ul").classList.toggle("show");
    });
  </script>
</body>
</html>
