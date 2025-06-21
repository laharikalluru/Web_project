<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/db_connection.php';

// Get cart items
$stmt = $conn->prepare("
    SELECT c.quantity, p.* 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
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

// Process checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start transaction
    $conn->begin_transaction();

    try {
        // Create order
        $stmt = $conn->prepare("
            INSERT INTO orders (user_id, total_amount, status, created_at, updated_at)
            VALUES (?, ?, 'pending', NOW(), NOW())
        ");
        $stmt->bind_param("id", $_SESSION['user_id'], $total);
        $stmt->execute();
        $order_id = $conn->insert_id;

        // Add order items
        $stmt = $conn->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");

        // Update product stock and add order items
        foreach ($cart_items as $item) {
            // Check stock
            $check_stock = $conn->prepare("SELECT stock FROM products WHERE id = ? FOR UPDATE");
            $check_stock->bind_param("i", $item['id']);
            $check_stock->execute();
            $stock_result = $check_stock->get_result();
            $current_stock = $stock_result->fetch_assoc()['stock'];

            if ($current_stock < $item['quantity']) {
                throw new Exception("Not enough stock for " . $item['name']);
            }

            // Update stock
            $new_stock = $current_stock - $item['quantity'];
            $update_stock = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
            $update_stock->bind_param("ii", $new_stock, $item['id']);
            $update_stock->execute();

            // Add order item
            $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }

        // Clear cart
        $clear_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $clear_cart->bind_param("i", $_SESSION['user_id']);
        $clear_cart->execute();

        // Commit transaction
        $conn->commit();

        // Redirect to order confirmation
        header("Location: order-confirmation.php?order_id=" . $order_id);
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $error_message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Little Learners Emporium</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .checkout-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
        }

        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }
        }

        .checkout-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .order-summary {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            align-self: start;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 16px;
        }

        .form-group input:focus {
            border-color: #3498db;
            outline: none;
        }

        .error-message {
            color: #e74c3c;
            background: #fde8e8;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-name {
            color: #2c3e50;
        }

        .item-price {
            color: #e74c3c;
            font-weight: bold;
        }

        .order-total {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            display: flex;
            justify-content: space-between;
            font-size: 1.2em;
            font-weight: bold;
            color: #2c3e50;
        }

        .place-order-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 30px;
        }

        .place-order-btn:hover {
            background: #2980b9;
        }

        .place-order-btn:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }

        .section-title {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="checkout-container">
        <div class="checkout-form">
            <h2 class="section-title">Shipping Information</h2>
            
            <?php if (isset($error_message)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form method="POST" id="checkout-form">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="address">Street Address</label>
                    <input type="text" id="address" name="address" required>
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" required>
                </div>

                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" id="state" name="state" required>
                </div>

                <div class="form-group">
                    <label for="zip">ZIP Code</label>
                    <input type="text" id="zip" name="zip" required pattern="[0-9]{5}">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" required pattern="[0-9]{10}">
                </div>

                <button type="submit" class="place-order-btn" <?php echo empty($cart_items) ? 'disabled' : ''; ?>>
                    Place Order
                </button>
            </form>
        </div>

        <div class="order-summary">
            <h2 class="section-title">Order Summary</h2>
            
            <?php if (empty($cart_items)): ?>
                <p>Your cart is empty</p>
            <?php else: ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="order-item">
                        <span class="item-name">
                            <?php echo htmlspecialchars($item['name']); ?> 
                            (×<?php echo $item['quantity']; ?>)
                        </span>
                        <span class="item-price">
                            $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </span>
                    </div>
                <?php endforeach; ?>

                <div class="order-total">
                    <span>Total:</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        const requiredFields = ['full_name', 'email', 'address', 'city', 'state', 'zip', 'phone'];
        let isValid = true;

        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                input.style.borderColor = '#e74c3c';
                isValid = false;
            } else {
                input.style.borderColor = '#e0e0e0';
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields');
        }
    });
    </script>
</body>
</html>
