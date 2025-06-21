<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/db_connection.php';

// Handle quantity updates and item removal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $cart_id = $_POST['cart_id'];
        $quantity = max(1, intval($_POST['quantity']));
        
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $quantity, $cart_id, $_SESSION['user_id']);
        $stmt->execute();
    }
    
    if (isset($_POST['remove_item'])) {
        $cart_id = $_POST['cart_id'];
        
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cart_id, $_SESSION['user_id']);
        $stmt->execute();
    }
    
    // Redirect to prevent form resubmission
    header("Location: cart.php");
    exit();
}

// Get cart items with product details
$stmt = $conn->prepare("
    SELECT c.id as cart_id, c.quantity, p.* 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
    ORDER BY c.created_at DESC
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Little Learners Emporium</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .cart-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .cart-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .cart-header h1 {
            font-size: 2.5em;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .cart-empty {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .cart-empty h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .cart-empty a {
            display: inline-block;
            padding: 12px 24px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        .cart-empty a:hover {
            background: #2980b9;
        }

        .cart-items {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 100px;
            height: 100px;
            margin-right: 20px;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-size: 1.2em;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .item-price {
            color: #e74c3c;
            font-weight: bold;
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .item-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-control input {
            width: 60px;
            padding: 8px;
            text-align: center;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
        }

        .update-btn, .remove-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .update-btn {
            background: #2ecc71;
            color: white;
        }

        .update-btn:hover {
            background: #27ae60;
        }

        .remove-btn {
            background: #e74c3c;
            color: white;
        }

        .remove-btn:hover {
            background: #c0392b;
        }

        .cart-summary {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-top: 2px solid #eee;
            font-size: 1.2em;
        }

        .checkout-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background: #3498db;
            color: white;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 6px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .checkout-btn:hover {
            background: #2980b9;
        }

        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                text-align: center;
            }

            .item-image {
                margin-right: 0;
                margin-bottom: 20px;
            }

            .item-actions {
                flex-direction: column;
                gap: 10px;
            }

            .quantity-control {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="cart-container">
        <div class="cart-header">
            <h1>Shopping Cart</h1>
            <p>Review your items and proceed to checkout</p>
        </div>

        <?php if (empty($cart_items)): ?>
            <div class="cart-empty">
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added any items to your cart yet.</p>
                <a href="catalog.php">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <div class="item-image">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                 onerror="this.src='images/placeholder.jpg'">
                        </div>
                        <div class="item-details">
                            <h3 class="item-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <div class="item-price">$<?php echo number_format($item['price'], 2); ?></div>
                            <div class="item-actions">
                                <form method="POST" class="quantity-control">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                    <button type="submit" name="update_quantity" class="update-btn">Update</button>
                                </form>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <button type="submit" name="remove_item" class="remove-btn">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <div class="cart-total">
                    <span>Total:</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
    // Prevent negative quantities
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('change', function() {
            if (this.value < 1) this.value = 1;
        });
    });
    </script>
</body>
</html>
