<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/db_connection.php';

// Create wishlist table if it doesn't exist
$createWishlistTable = "CREATE TABLE IF NOT EXISTS wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    UNIQUE KEY unique_wishlist_item (user_id, product_id)
)";

if (!$conn->query($createWishlistTable)) {
    die("Error creating wishlist table: " . $conn->error);
}

// Get user ID from email
$user_email = $_SESSION['user_email'];
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Error: User not found.";
    exit();
}

$user_id = $user['id'];

// Handle add/remove from wishlist
if (isset($_POST['action']) && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];
    
    if ($action === 'add') {
        // Check if product exists
        $stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            exit();
        }
        
        // Add to wishlist
        $stmt = $conn->prepare("INSERT IGNORE INTO wishlist (user_id, product_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    } elseif ($action === 'remove') {
        // Remove from wishlist
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }
    
    header("Location: wishlist.php");
    exit();
}

// Fetch wishlist products
try {
    $query = "SELECT p.id, p.name, p.description, p.price, p.image_url, p.category, p.age_range 
              FROM wishlist w
              INNER JOIN products p ON w.product_id = p.id
              WHERE w.user_id = ?
              ORDER BY w.created_at DESC";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - Little Learners Emporium</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .wishlist-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            min-height: calc(100vh - 400px);
        }

        .wishlist-header {
            text-align: center;
            margin-bottom: 40px;
            color: #4e342e;
        }

        .wishlist-header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: #4e342e;
        }

        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 220px;
            overflow: hidden;
            position: relative;
            background: #f5f5f5;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .product-info {
            padding: 20px;
        }

        .product-name {
            font-size: 1.2em;
            color: #4e342e;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.9em;
            color: #666;
        }

        .product-price {
            font-size: 1.3em;
            color: #2e7d32;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .product-description {
            color: #666;
            margin-bottom: 15px;
            font-size: 0.9em;
            line-height: 1.5;
        }

        .product-actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9em;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font-weight: bold;
        }

        .cart-btn {
            background: #2e7d32;
            color: white;
        }

        .cart-btn:hover {
            background: #1b5e20;
        }

        .remove-btn {
            background: #c62828;
            color: white;
        }

        .remove-btn:hover {
            background: #b71c1c;
        }

        .empty-wishlist {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            color: #666;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .empty-wishlist h2 {
            color: #4e342e;
            margin-bottom: 20px;
        }

        .empty-wishlist a {
            color: #2e7d32;
            text-decoration: none;
            font-weight: bold;
        }

        .empty-wishlist a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .wishlist-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }
            
            .wishlist-container {
                padding: 0 15px;
            }
        }

        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            display: none;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="wishlist-container">
        <div class="wishlist-header">
            <h1>💖 My Wishlist</h1>
            <p>Your favorite educational toys and learning materials</p>
        </div>

        <div id="successMessage" class="success-message"></div>

        <?php if (empty($products)): ?>
            <div class="empty-wishlist">
                <h2>Your wishlist is empty</h2>
                <p>Browse our <a href="catalog.php">toy catalog</a> to add your favorite items!</p>
            </div>
        <?php else: ?>
            <div class="wishlist-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 onerror="this.src='images/placeholder.jpg'">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <div class="product-meta">
                                <span>Category: <?php echo htmlspecialchars($product['category']); ?></span>
                                <span>Age: <?php echo htmlspecialchars($product['age_range']); ?></span>
                            </div>
                            <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                            <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="product-actions">
                                <button class="action-btn cart-btn" onclick="addToCart(<?php echo $product['id']; ?>)">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                                <form method="post" style="display: inline; flex: 1;">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="action-btn remove-btn" onclick="return confirm('Are you sure you want to remove this item from your wishlist?');">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
    function addToCart(productId) {
        fetch('ajax/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            const successMessage = document.getElementById('successMessage');
            successMessage.textContent = data.success ? 'Product added to cart!' : (data.message || 'Error adding product to cart');
            successMessage.style.display = 'block';
            successMessage.style.backgroundColor = data.success ? '#4CAF50' : '#f44336';
            
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding product to cart');
        });
    }
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
