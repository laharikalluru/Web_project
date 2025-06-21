<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Little Learners</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="dashboard">
    <h2>👋 Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <p>What would you like to do today?</p>

    <div class="dashboard-links">
      <a href="catalog.php">🧸 Browse Toy Catalog</a>
      <a href="wishlist.php">💖 View Wishlist</a>
      <a href="giftfinder.php">🎁 Use Gift Finder</a>
      <a href="logout.php" class="logout-btn">🚪 Logout</a>
    </div>
  </div>
  <script src="app.js"></script>
</body>
</html>
