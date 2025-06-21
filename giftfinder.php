<?php
session_start();
include("includes/db.php");
include("includes/header.php");
$results = [];
$searched = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $age = $_POST["age_group"];
  $interest = $_POST["category"];
  $searched = true;

  $stmt = $conn->prepare("SELECT * FROM toys WHERE age_group = ? AND category = ?");
  $stmt->bind_param("ss", $age, $interest);
  $stmt->execute();
  $results = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Gift Finder - Little Learners</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .gift-form {
      max-width: 500px;
      margin: 30px auto;
      background: #fffbe7;
      padding: 25px;
      border-radius: 10px;
      text-align: center;
    }

    .gift-form select, .gift-form button {
      padding: 10px;
      font-size: 1rem;
      margin: 10px 0;
      width: 80%;
      border-radius: 6px;
    }

    .gift-results {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 30px;
    }

    .gift-card {
      width: 250px;
      background: #fff;
      border-radius: 10px;
      padding: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      text-align: center;
    }

    .gift-card img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 10px;
    }

    .no-results {
      text-align: center;
      font-size: 1.1rem;
      margin-top: 20px;
      color: #e53935;
    }
  </style>
</head>
<body>

  <h2>🎁 Find the Perfect Gift</h2>

  <div class="gift-form">
    <form method="POST" action="">
      <label for="age_group"><strong>Choose Age Group:</strong></label><br>
      <select name="age_group" required>
        <option value="">--Select--</option>
        <option value="0-2">0–2 Years</option>
        <option value="3-5">3–5 Years</option>
        <option value="6-8">6–8 Years</option>
      </select><br>

      <label for="category"><strong>Select Interest:</strong></label><br>
      <select name="category" required>
        <option value="">--Select--</option>
        <option value="Educational">Educational</option>
        <option value="Grasping">Grasping</option>
        <option value="Color ID">Color Identification</option>
    
      </select><br>

      <button type="submit">🔍 Find Toys</button>
    </form>
  </div>

  <?php if ($searched): ?>
    <?php if ($results->num_rows > 0): ?>
      <div class="gift-results">
        <?php while ($toy = $results->fetch_assoc()): ?>
          <div class="gift-card">
            <img src="<?php echo $toy['image']; ?>" alt="<?php echo $toy['name']; ?>">
            <h3><?php echo $toy['name']; ?></h3>
            <p><?php echo $toy['description']; ?></p>
            <a href="toy-details.php?id=<?php echo $toy['id']; ?>" class="add-btn">🔍 View Details</a>

          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="no-results">😕 No toys matched your selection. Try a different filter!</div>
    <?php endif; ?>
  <?php endif; ?>
  <?php include("includes/footer.php"); ?>
  <script src="app.js"></script>
</body>
</html>
