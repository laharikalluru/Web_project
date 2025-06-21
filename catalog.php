<?php
session_start();

// Database connection
require_once 'includes/db_connection.php';

// Get age range filter
$age_range = isset($_GET['age_range']) ? $_GET['age_range'] : 'default';

// Define available age ranges
$available_age_ranges = ['default', 'all', '0-2', '3-5', '6-8'];

// Build the query
$query = "SELECT * FROM products WHERE 1=1";
$params = array();
$types = "";

if (!empty($age_range) && $age_range !== 'default' && $age_range !== 'all') {
    $query .= " AND age_range = ?";
    $params[] = $age_range;
    $types .= "s";
}

// Get age ranges for filter
$age_query = "SELECT DISTINCT age_range FROM products ORDER BY age_range";
$age_ranges = $conn->query($age_query)->fetch_all(MYSQLI_ASSOC);

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toy Catalog - Little Learners Emporium</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .catalog-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .catalog-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .catalog-header h1 {
            font-size: 2.5em;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .filter-section {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .category-select {
            padding: 10px 20px;
            font-size: 1em;
            border: 2px solid #3498db;
            border-radius: 25px;
            background: white;
            color: #3498db;
            cursor: pointer;
            min-width: 200px;
            text-align: center;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%233498db' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1em center;
            background-size: 1em;
        }

        .category-select:hover {
            border-color: #2980b9;
            color: #2980b9;
        }

        .category-select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image {
            width: 100%;
            height: 200px;
            overflow: hidden;
            position: relative;
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
            padding: 15px;
            text-align: center;
        }

        .product-name {
            font-size: 1.1em;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .product-price {
            font-size: 1.2em;
            color: #e74c3c;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .view-details {
            display: inline-block;
            padding: 8px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: background 0.3s ease;
        }

        .view-details:hover {
            background: #2980b9;
        }

        .product-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .modal-content {
            position: relative;
            background: white;
            width: 80%;
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 1.5em;
            color: #666;
            cursor: pointer;
        }

        .modal-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .modal-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .modal-info h2 {
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .modal-price {
            font-size: 1.5em;
            color: #e74c3c;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .modal-description {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .modal-btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .wishlist-btn {
            background: #e74c3c;
            color: white;
        }

        .cart-btn {
            background: #2ecc71;
            color: white;
        }

        .shipping-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .shipping-info h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .shipping-details {
            color: #666;
            font-size: 0.9em;
            line-height: 1.5;
        }

        @media (max-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .modal-content {
                width: 95%;
                margin: 20px auto;
            }

            .modal-details {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="catalog-container">
        <div class="catalog-header">
            <h1>Toys Catalog</h1>
            <p>Browse our collection by age group</p>
        </div>

        <div class="filter-section">
            <select class="category-select" onchange="window.location.href='catalog.php?age_range=' + this.value">
                <option value="default" <?php echo $age_range === 'default' ? 'selected' : ''; ?>>Categories</option>
                <option value="all" <?php echo $age_range === 'all' ? 'selected' : ''; ?>>All Ages</option>
                <option value="0-2" <?php echo $age_range === '0-2' ? 'selected' : ''; ?>>0-2 Years</option>
                <option value="3-5" <?php echo $age_range === '3-5' ? 'selected' : ''; ?>>3-5 Years</option>
                <option value="6-8" <?php echo $age_range === '6-8' ? 'selected' : ''; ?>>6-8 Years</option>
            </select>
        </div>

        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             onerror="this.src='images/placeholder.jpg'">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                        <a href="product-details.php?id=<?php echo $product['id']; ?>" class="view-details">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="productModal" class="product-modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <img id="modalImage" class="modal-image" src="" alt="">
            <div class="modal-details">
                <div class="modal-info">
                    <h2 id="modalName"></h2>
                    <div id="modalPrice" class="modal-price"></div>
                    <p id="modalDescription" class="modal-description"></p>
                    <div class="modal-actions">
                        <button class="modal-btn wishlist-btn" onclick="addToWishlist()">
                            <i class="fas fa-heart"></i> Add to Wishlist
                        </button>
                        <button class="modal-btn cart-btn" onclick="addToCart()">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </div>
                </div>
                <div class="shipping-info">
                    <h3>Shipping Information</h3>
                    <div class="shipping-details">
                        <p>Standard Shipping: $5.99 (3-5 business days)</p>
                        <p>Express Shipping: $12.99 (1-2 business days)</p>
                        <p>Free Shipping on orders over $50</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentProduct = null;

        function showProductDetails(product) {
            currentProduct = product;
            document.getElementById('modalImage').src = product.image_url;
            document.getElementById('modalName').textContent = product.name;
            document.getElementById('modalPrice').textContent = '$' + parseFloat(product.price).toFixed(2);
            document.getElementById('modalDescription').textContent = product.description;
            document.getElementById('productModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('productModal').style.display = 'none';
            currentProduct = null;
        }

        function addToWishlist() {
            if (!currentProduct) return;
            
            fetch('ajax/add_to_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: currentProduct.id
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product added to wishlist!');
                } else {
                    alert(data.message || 'Error adding product to wishlist');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding product to wishlist');
            });
        }

        function addToCart() {
            if (!currentProduct) return;
            
            fetch('ajax/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: currentProduct.id
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product added to cart!');
                    closeModal();
                } else {
                    alert(data.message || 'Error adding product to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding product to cart');
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == document.getElementById('productModal')) {
                closeModal();
            }
        }
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
