<?php
session_start();
include("includes/db.php");
include("includes/header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Activities - Little Learners Emporium</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .activities-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .age-filter {
            text-align: center;
            margin-bottom: 30px;
        }

        .age-filter button {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 20px;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .age-filter button.active {
            background: #5db075;
            color: white;
        }

        .activities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .activity-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .activity-card:hover {
            transform: translateY(-5px);
        }

        .activity-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .activity-category {
            display: inline-block;
            padding: 5px 15px;
            background: #e8f5e9;
            color: #2e7d32;
            border-radius: 15px;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .activity-title {
            font-size: 1.2rem;
            margin: 10px 0;
            color: #333;
        }

        .activity-description {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .learning-outcomes {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }

        .learning-outcomes h4 {
            color: #333;
            margin-bottom: 10px;
        }

        .learning-outcomes ul {
            list-style: none;
            padding: 0;
        }

        .learning-outcomes li {
            color: #666;
            font-size: 0.9rem;
            margin: 5px 0;
            padding-left: 20px;
            position: relative;
        }

        .learning-outcomes li:before {
            content: "✓";
            color: #5db075;
            position: absolute;
            left: 0;
        }

        .materials-needed {
            margin-top: 15px;
            font-size: 0.9rem;
            color: #666;
        }

        .difficulty {
            display: flex;
            align-items: center;
            margin-top: 15px;
            color: #666;
        }

        .difficulty span {
            margin-right: 10px;
        }

        .difficulty-level {
            display: flex;
            gap: 5px;
        }

        .difficulty-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ddd;
        }

        .difficulty-dot.active {
            background: #5db075;
        }

        .start-activity-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #5db075;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
            transition: background 0.3s ease;
        }

        .start-activity-btn:hover {
            background: #4a8f5e;
        }
    </style>
</head>
<body>
    <div class="activities-container">
        <h1>🎨 Learning Activities</h1>
        <p class="intro-text">Discover age-appropriate educational activities that make learning fun!</p>

        <div class="age-filter">
            <button data-age="3-5">3-5 Years</button>
            <button data-age="6-8">6-8 Years</button>
        </div>

        <div class="activities-grid">
            <!-- 3-5 Years Activities -->
            <div class="activity-card" data-age="3-5">
                <img src="images/activities/icons/color-sorting.jpg" alt="Color Sorting Activity" class="activity-image">
                <span class="activity-category">Color Recognition</span>
                <h3 class="activity-title">Rainbow Sorting Play</h3>
                <p class="activity-description">
                    A fun, hands-on activity where children learn to identify and sort objects by color.
                </p>
                <div class="learning-outcomes">
                    <h4>Learning Outcomes:</h4>
                    <ul>
                        <li>Color recognition</li>
                        <li>Fine motor skills</li>
                        <li>Basic sorting concepts</li>
                    </ul>
                </div>
                <div class="materials-needed">
                    <strong>Materials:</strong> Colored blocks, sorting containers
                </div>
                <div class="difficulty">
                    <span>Difficulty:</span>
                    <div class="difficulty-level">
                        <div class="difficulty-dot active"></div>
                        <div class="difficulty-dot"></div>
                        <div class="difficulty-dot"></div>
                    </div>
                </div>
                <a href="activities/color-sorting.php" class="start-activity-btn">Start Activity</a>
            </div>

            <div class="activity-card" data-age="3-5">
                <img src="images/activities/icons/texture-play.jpg" alt="Texture Play" class="activity-image">
                <span class="activity-category">Sensory Development</span>
                <h3 class="activity-title">Texture Explorer</h3>
                <p class="activity-description">
                    Explore different textures through interactive play to develop sensory awareness and vocabulary.
                </p>
                <div class="learning-outcomes">
                    <h4>Learning Outcomes:</h4>
                    <ul>
                        <li>Sensory development</li>
                        <li>Vocabulary building</li>
                        <li>Tactile awareness</li>
                    </ul>
                </div>
                <div class="materials-needed">
                    <strong>Materials:</strong> Various textured materials, sorting cards
                </div>
                <div class="difficulty">
                    <span>Difficulty:</span>
                    <div class="difficulty-level">
                        <div class="difficulty-dot active"></div>
                        <div class="difficulty-dot"></div>
                        <div class="difficulty-dot"></div>
                    </div>
                </div>
                <a href="activities/texture-play.php" class="start-activity-btn">Start Activity</a>
            </div>

            <!-- 6-8 Years Activities -->
            <div class="activity-card" data-age="6-8">
                <img src="images/activities/icons/math-patterns.jpg" alt="Math Pattern Challenge" class="activity-image">
                <span class="activity-category">Mathematical Thinking</span>
                <h3 class="activity-title">Math Pattern Challenge</h3>
                <p class="activity-description">
                    An engaging activity where children discover and create mathematical patterns using numbers, shapes, and logic.
                </p>
                <div class="learning-outcomes">
                    <h4>Learning Outcomes:</h4>
                    <ul>
                        <li>Mathematical reasoning</li>
                        <li>Pattern recognition</li>
                        <li>Problem-solving skills</li>
                    </ul>
                </div>
                <div class="materials-needed">
                    <strong>Materials:</strong> Number cards, shape blocks, pattern worksheets
                </div>
                <div class="difficulty">
                    <span>Difficulty:</span>
                    <div class="difficulty-level">
                        <div class="difficulty-dot active"></div>
                        <div class="difficulty-dot active"></div>
                        <div class="difficulty-dot"></div>
                    </div>
                </div>
                <a href="activities/math-patterns.php" class="start-activity-btn">Start Activity</a>
            </div>

            <div class="activity-card" data-age="6-8">
                <img src="images/activities/icons/creative-writing.jpg" alt="Creative Writing Adventure" class="activity-image">
                <span class="activity-category">Language Arts</span>
                <h3 class="activity-title">Creative Writing Adventure</h3>
                <p class="activity-description">
                    A fun storytelling activity that encourages children to create their own stories using imagination and vocabulary skills.
                </p>
                <div class="learning-outcomes">
                    <h4>Learning Outcomes:</h4>
                    <ul>
                        <li>Creative writing skills</li>
                        <li>Vocabulary enhancement</li>
                        <li>Story structure understanding</li>
                    </ul>
                </div>
                <div class="materials-needed">
                    <strong>Materials:</strong> Story prompts, writing materials, character cards
                </div>
                <div class="difficulty">
                    <span>Difficulty:</span>
                    <div class="difficulty-level">
                        <div class="difficulty-dot active"></div>
                        <div class="difficulty-dot active"></div>
                        <div class="difficulty-dot"></div>
                    </div>
                </div>
                <a href="activities/creative-writing.php" class="start-activity-btn">Start Activity</a>
            </div>

            <!-- Add more activity cards for different age groups -->
        </div>
    </div>

    <script>
        // Filter activities by age group
        document.querySelectorAll('.age-filter button').forEach(button => {
            button.addEventListener('click', () => {
                // Update active button
                document.querySelector('.age-filter button.active')?.classList.remove('active');
                button.classList.add('active');

                // Filter activities
                const selectedAge = button.dataset.age;
                document.querySelectorAll('.activity-card').forEach(card => {
                    if (card.dataset.age === selectedAge) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Initialize with 3-5 years selected
        document.querySelector('[data-age="3-5"]').click();
    </script>

    <?php include("includes/footer.php"); ?>
</body>
</html> 