<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Little Learners Emporium - Fun Learning Toys</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/components/games.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
  <!-- Header & Navigation -->
  <header>
    <div class="header-container">
      <div class="logo">
        <h1>👶 Little Learners Emporium</h1>
      </div>
      <nav>
        <a href="index.php" class="active">Home</a>
        <?php if (isset($_SESSION['user_email'])): ?>
          <a href="catalog.php">Toy Catalog</a>
          <a href="learning-activities.php">Learning Activities</a>
          <a href="wishlist.php">Wishlist</a>
          <a href="games.php">Fun Games</a>
          <a href="cart.php">Cart</a>
          <a href="logout.php" class="logout-btn">Logout (<?php echo htmlspecialchars($_SESSION['user_email']); ?>)</a>
        <?php else: ?>
          <a href="login.php" class="login-btn">🔐 Login</a>
          <a href="register.php" class="register-btn">📝 Register</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <!-- Hero Banner with Carousel -->
  <section class="hero">
    <div class="hero-content">
      <h2>Discover the Joy of Learning Through Play</h2>
      <p>Smart, colorful, and engaging toys designed for curious young minds</p>
      <div class="hero-buttons">
        <a href="catalog.php" class="btn primary">Explore Toys</a>
      </div>
    </div>
    <div class="hero-carousel">
      <div class="carousel-item active">
        <img src="images/images/banners/hero-banner-2.jpg" alt="Educational Toys">
      </div>
      <div class="carousel-item">
        <img src="images/images/banners/hero-banner.jpg" alt="STEM Learning">
      </div>
      <div class="carousel-item">
        <img src="images/images/banners/hero-banner-3.jpg" alt="Creative Play">
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features">
    <div class="feature-card">
      <i class="fas fa-brain"></i>
      <h3>STEM Learning</h3>
      <p>Develop critical thinking and problem-solving skills</p>
    </div>
    <div class="feature-card">
      <i class="fas fa-palette"></i>
      <h3>Creative Play</h3>
      <p>Spark imagination and artistic expression</p>
    </div>
    <div class="feature-card">
      <i class="fas fa-hand-holding-heart"></i>
      <h3>Safe & Durable</h3>
      <p>High-quality materials for worry-free play</p>
    </div>
  </section>

  <!-- Popular Toys Section -->
  <section class="popular-toys">
    <h2>Featured Toys</h2>
    <div class="toy-grid">
      <a href="catalog.php?toy=color-blocks" class="toy-card">
        <div class="toy-image">
          <img src="images/images/toys/color-blocks.jpg" alt="Color Blocks" loading="lazy" width="280" height="200">
          <div class="toy-overlay">
            <div class="overlay-content">
              <button class="quick-view">Quick View</button>
              <div class="overlay-details">
                <p>Interactive color learning blocks</p>
                <p>Perfect for early development</p>
              </div>
            </div>
          </div>
        </div>
        <h3>🧸 Color Blocks</h3>
        <p class="age-range">Ages 0-2</p>
        <p class="features">Color Blocks are soft, colorful, and perfect for toddlers to stack and sort. They boost motor skills and early learning through fun play.</p>
        <div class="rating">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star-half-alt"></i>
          <span>(42 reviews)</span>
        </div>
      </a>

      <a href="catalog.php?toy=alphabet-puzzle" class="toy-card">
        <div class="toy-image">
          <img src="images/images/toys/alphabet-puzzle.jpg" alt="Alphabet Puzzle" loading="lazy" width="280" height="200">
          <div class="toy-overlay">
            <div class="overlay-content">
              <button class="quick-view">Quick View</button>
              <div class="overlay-details">
                <p>Fun way to learn ABCs</p>
                <p>Durable wooden pieces</p>
              </div>
            </div>
          </div>
        </div>
        <h3>🔤 Alphabet Puzzle</h3>
        <p class="age-range">Ages 3-5</p>
        <p class="features">Alphabet Puzzle makes learning letters fun and hands-on for kids. It helps build early literacy and fine motor skills through play.

</p>
        <div class="rating">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="far fa-star"></i>
          <span>(38 reviews)</span>
        </div>
      </a>

      <a href="catalog.php?toy=number-match" class="toy-card">
        <div class="toy-image">
          <img src="images/images/toys/number-match.jpg" alt="Number Match" loading="lazy" width="280" height="200">
          <div class="toy-overlay">
            <div class="overlay-content">
              <button class="quick-view">Quick View</button>
              <div class="overlay-details">
                <p>Interactive number learning</p>
                <p>Perfect for counting practice</p>
              </div>
            </div>
          </div>
        </div>
        <h3>🔢 Number Match</h3>
        <p class="age-range">Ages 6-8</p>
        <p class="features">Match numbers with shapes to make counting fun and easy. This toy boosts early math skills and number recognition through playful learning./p>
        <div class="rating">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <span>(56 reviews)</span>
        </div>
      </a>
    </div>
  </section>

  <!-- Fun Games Section -->
  <section class="fun-games">
    <h2>Fun Learning Games</h2>
    <div class="games-grid">
      <div class="game-card" onclick="startGame('memory')">
        <div class="game-icon">
          <i class="fas fa-brain"></i>
        </div>
        <h3>Memory Match</h3>
        <p>Match the pairs and improve your memory!</p>
        <button class="play-btn">Play Now</button>
      </div>

      <!-- <div class="game-card" onclick="startGame('puzzle')">
        <div class="game-icon">
          <i class="fas fa-puzzle-piece"></i>
        </div>
        <h3>Shape Puzzle</h3>
        <p>Solve fun shape puzzles!</p>
        <button class="play-btn">Play Now</button>
      </div> -->

      <div class="game-card" onclick="startGame('counting')">
        <div class="game-icon">
          <i class="fas fa-calculator"></i>
        </div>
        <h3>Counting Fun</h3>
        <p>Learn numbers while having fun!</p>
        <button class="play-btn">Play Now</button>
      </div>
    </div>
  </section>

  <!-- Newsletter Section -->
  <?php include 'includes/newsletter.php'; ?>

  <!-- Footer -->
  <footer>
    <div class="footer-content">
      <div class="footer-section">
        <h3>About Us</h3>
        <p>Making learning fun and engaging for little minds since 2020.</p>
      </div>
      <div class="footer-section">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="catalog.php">Shop</a></li>
          <li><a href="learning-activities.php">Learning Activities</a></li>
          <li><a href="games.php">Games</a></li>
          <li><a href="about.php">About Us</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>
      <div class="footer-section">
        <h3>Connect With Us</h3>
        <div class="social-links">
          <a href="#"><i class="fab fa-facebook"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-pinterest"></i></a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2024 Little Learners Emporium. All rights reserved.</p>
    </div>
  </footer>

  <!-- Game Modal -->
  <div id="gameModal" class="modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <div id="gameContainer"></div>
    </div>
  </div>

  <script src="js/app.js"></script>
  <script src="js/games/memory.js"></script>
  <script src="js/games/puzzle.js"></script>
  <script src="js/games/counting.js"></script>
</body>
</html>
