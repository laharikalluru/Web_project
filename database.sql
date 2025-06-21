-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS little_learners;
USE little_learners;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- Toys Table
CREATE TABLE IF NOT EXISTS toys (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  age_group VARCHAR(10) NOT NULL,
  category VARCHAR(50) NOT NULL,
  mood VARCHAR(50) NOT NULL,
  image VARCHAR(255),
  description TEXT,
  benefit TEXT,
  certification TEXT
);

-- Wishlist Table
CREATE TABLE IF NOT EXISTS wishlist (
  user_id INT NOT NULL,
  toy_id INT NOT NULL,
  PRIMARY KEY (user_id, toy_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (toy_id) REFERENCES toys(id) ON DELETE CASCADE
);

-- Ratings Table
CREATE TABLE IF NOT EXISTS ratings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  toy_id INT,
  rating INT,
  UNIQUE KEY unique_rating (user_id, toy_id)
);

-- Insert sample toys
INSERT INTO toys (name, age_group, category, mood, image, description, benefit, certification) VALUES
('Color Blocks', '0-2', 'Grasping', 'color', 'images/toys/color-blocks.jpg', 'Brightly colored stacking blocks', 'Improves motor skills and color recognition', 'BPA-Free'),
('Alphabet Puzzle', '3-5', 'Educational', 'build', 'images/toys/alphabet-puzzle.jpg', 'Learn letters by placing them in slots', 'Develops alphabet recognition and logic', 'EN71 Certified'),
('Number Match', '6-8', 'Math Skills', 'build', 'images/toys/number-match.jpg', 'Match numbers to word tiles', 'Boosts counting and reading skills', 'Non-toxic');

-- Insert color identification toys for 0-2 age group
INSERT INTO toys (name, age_group, category, mood, image, description, benefit, certification) VALUES
('Rainbow Soft Blocks', '0-2', 'Color ID', 'color', 'images/toys/rainbow-blocks.jpg', 'Soft, squeezable blocks in primary colors perfect for tiny hands', 'Develops color recognition and sensory skills', 'BPA-Free, Non-toxic'),
('Color Discovery Balls', '0-2', 'Color ID', 'color', 'images/toys/color-balls.jpg', 'Set of textured balls in different colors with rattling sounds', 'Enhances color learning and sensory development', 'EN71 Certified, Non-toxic'),
('First Colors Board Book', '0-2', 'Color ID', 'learn', 'images/toys/color-book.jpg', 'Sturdy board book with large, colorful pictures', 'Introduces basic colors through familiar objects', 'Child-Safe Ink'),
('Color Pop Beads', '0-2', 'Color ID', 'color', 'images/toys/pop-beads.jpg', 'Large, easy-to-grasp beads in bright colors', 'Improves color recognition and fine motor skills', 'Non-toxic, BPA-Free'),
('Color Match Cups', '0-2', 'Color ID', 'sort', 'images/toys/match-cups.jpg', 'Stacking cups with color matching activities', 'Teaches color matching and spatial awareness', 'Food-Grade Material');

-- Insert test user (username: test, password: test123)
INSERT INTO users (username, email, password)
VALUES ('test', 'test2@example.com', '$2y$10$UnG6LyVEc3L8dEiZ0TxGOVum3IUsuRRpGj8Ru9UheVPbcVCRm5FKa');

