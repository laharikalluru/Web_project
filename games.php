<?php
// Start the session before any output
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fun Educational Games - Little Learners Emporium</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .games-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .games-header {
            text-align: center;
            margin-bottom: 50px;
            padding: 0 20px;
        }

        .games-header h1 {
            font-size: 3.5em;
            color: #2c3e50;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #2c3e50, #3498db);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .games-header p {
            font-size: 1.4em;
            color: #666;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .game-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .game-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .game-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #3498db, #2ecc71);
            border-radius: 24px 24px 0 0;
        }

        .game-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            background: #f8f9fa;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3em;
            color: #3498db;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .game-card:hover .game-icon {
            transform: scale(1.1) rotate(5deg);
            color: #2ecc71;
        }

        .game-title {
            font-size: 1.8em;
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .game-description {
            color: #666;
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 25px;
            min-height: 60px;
        }

        .play-button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #3498db, #2ecc71);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1.1em;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .play-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, #2ecc71, #3498db);
        }

        @media (max-width: 768px) {
            .games-header h1 {
                font-size: 2.8em;
            }

            .games-header p {
                font-size: 1.2em;
                padding: 0 20px;
            }

            .games-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 20px;
                padding: 15px;
            }

            .game-card {
                padding: 25px;
            }
        }

        @media (max-width: 480px) {
            .games-header h1 {
                font-size: 2.2em;
            }

            .games-header p {
                font-size: 1.1em;
            }

            .game-title {
                font-size: 1.5em;
            }

            .game-description {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="games-container">
        <div class="games-header">
            <h1>Fun Educational Games</h1>
            <p>Explore our collection of interactive educational games designed to make learning fun!</p>
        </div>

        <div class="games-grid">
            <div class="game-card">
                <div class="game-icon">
                    <img src="images/icons/alphabet.svg" alt="Alphabet Pop" width="60" height="60">
                </div>
                <h2 class="game-title">Alphabet Pop</h2>
                <p class="game-description">Pop bubbles in alphabetical order to improve letter recognition!</p>
                <a href="games/alphabet-pop.html" class="play-button">Play Now</a>
            </div>

            <div class="game-card">
                <div class="game-icon">
                    <img src="images/icons/palette.svg" alt="Color Match" width="60" height="60">
                </div>
                <h2 class="game-title">Color Match</h2>
                <p class="game-description">Match colors to enhance color recognition and memory!</p>
                <a href="games/color-match.html" class="play-button">Play Now</a>
            </div>

            <div class="game-card">
                <div class="game-icon">
                    <img src="images/icons/filter.svg" alt="Educational Filter" width="60" height="60">
                </div>
                <h2 class="game-title">Educational Filter</h2>
                <p class="game-description">Sort items into their correct categories!</p>
                <a href="games/educational-filter.html" class="play-button">Play Now</a>
            </div>

            <div class="game-card">
                <div class="game-icon">
                    <img src="images/icons/numbers.svg" alt="Numbers Puzzle" width="60" height="60">
                </div>
                <h2 class="game-title">Numbers Puzzle</h2>
                <p class="game-description">Arrange the numbers in order to improve number recognition!</p>
                <a href="games/numbers-puzzle.html" class="play-button">Play Now</a>
            </div>

            <div class="game-card">
                <div class="game-icon">
                    <img src="images/activities/icons/shape-sorter.jpg" alt="Shape Sorter" width="60" height="60">
                </div>
                <h2 class="game-title">Shape Sorter</h2>
                <p class="game-description">Sort different shapes to learn about basic geometry!</p>
                <a href="games/shape-sorter.html" class="play-button">Play Now</a>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 