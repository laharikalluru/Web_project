<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Check if order ID is provided
if (!isset($_GET['order_id'])) {
    header("Location: cart.php");
    exit();
}

require_once 'includes/db_connection.php';

// Get order details
$stmt = $conn->prepare("
    SELECT o.*, u.email 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->bind_param("ii", $_GET['order_id'], $_SESSION['user_id']);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header("Location: cart.php");
    exit();
}

// Get order items
$stmt = $conn->prepare("
    SELECT oi.*, p.name, p.image_url
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $_GET['order_id']);
$stmt->execute();
$order_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Little Learners Emporium</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .confirmation-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .confirmation-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .success-message {
            text-align: center;
            color: #27ae60;
            margin-bottom: 30px;
        }

        .success-message h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .success-message p {
            color: #7f8c8d;
            font-size: 1.1em;
        }

        .order-details {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }

        .order-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .order-info div {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
        }

        .order-info h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .order-info p {
            color: #7f8c8d;
            margin: 5px 0;
        }

        .items-list {
            margin-top: 30px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-details {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-info h4 {
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .item-info p {
            color: #7f8c8d;
            font-size: 0.9em;
        }

        .item-price {
            color: #e74c3c;
            font-weight: bold;
        }

        .order-total {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            text-align: right;
            font-size: 1.2em;
            color: #2c3e50;
        }

        .continue-shopping {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 24px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        .continue-shopping:hover {
            background: #2980b9;
        }

        @media (max-width: 768px) {
            .order-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="confirmation-container">
        <div class="confirmation-box">
            <div class="success-message">
                <h1>🎉 Thank You for Your Order!</h1>
                <p>Your order has been successfully placed and will be processed shortly.</p>
            </div>

            <div class="order-details">
                <div class="order-info">
                    <div>
                        <h3>Order Information</h3>
                        <p>Order #: <?php echo str_pad($order['id'], 8, '0', STR_PAD_LEFT); ?></p>
                        <p>Date: <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                        <p>Status: <?php echo ucfirst($order['status']); ?></p>
                    </div>
                    <div>
                        <h3>Customer Information</h3>
                        <p>Email: <?php echo htmlspecialchars($order['email']); ?></p>
                    </div>
                </div>

                <div class="items-list">
                    <h3>Order Items</h3>
                    <?php foreach ($order_items as $item): ?>
                        <div class="item">
                            <div class="item-details">
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                     class="item-image">
                                <div class="item-info">
                                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                    <p>Quantity: <?php echo $item['quantity']; ?></p>
                                </div>
                            </div>
                            <span class="item-price">
                                $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="order-total">
                    <strong>Total: $<?php echo number_format($order['total_amount'], 2); ?></strong>
                </div>

                <div style="text-align: center;">
                    <a href="index.php" class="continue-shopping">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 