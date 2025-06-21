<?php
session_start();
require_once 'includes/db_connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id'])) {
    header('Location: catalog.php');
    exit();
}

$product_id = (int)$_GET['id'];

// First, verify the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the products table exists
$table_check = $conn->query("SHOW TABLES LIKE 'toys'");
if ($table_check->num_rows > 0) {
    // Use the toys table
    $stmt = $conn->prepare("SELECT * FROM toys WHERE id = ?");
} else {
    // Use the products table
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
}

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    header('Location: catalog.php');
    exit();
}

// Get additional product images
$images_stmt = $conn->prepare("
    SELECT image_url 
    FROM product_images 
    WHERE product_id = ? 
    ORDER BY id ASC 
    LIMIT 4
");

if ($images_stmt) {
    $images_stmt->bind_param("i", $product_id);
    $images_stmt->execute();
    $images_result = $images_stmt->get_result();
    $product_images = [];
    while ($image = $images_result->fetch_assoc()) {
        $product_images[] = $image['image_url'];
    }
    $images_stmt->close();
} else {
    $product_images = [];
}

// If no additional images found, create dummy images based on the main image
if (empty($product_images)) {
    $main_image = $product['image_url'] ?? $product['image'];
    $image_parts = pathinfo($main_image);
    for ($i = 2; $i <= 5; $i++) {
        $product_images[] = $image_parts['dirname'] . '/' . $image_parts['filename'] . "-{$i}." . $image_parts['extension'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Details</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .product-details {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .image-gallery {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .gallery-image {
            width: 100%;
            height: 300px;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            background: #f5f5f5;
            position: relative;
            border: 3px solid transparent;
            transition: border-color 0.3s ease;
        }

        .gallery-image.selected {
            border-color: #2ecc71;
        }

        .gallery-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-image:hover img {
            transform: scale(1.05);
        }

        .image-price {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(46, 204, 113, 0.9);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 1.1em;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .product-info {
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: relative;
        }

        .product-title {
            font-size: 2em;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .product-price {
            font-size: 1.8em;
            color: #e74c3c;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .product-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .age-range {
            display: inline-block;
            padding: 8px 16px;
            background: #3498db;
            color: white;
            border-radius: 20px;
            margin-bottom: 20px;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
        }

        .action-btn {
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .add-to-cart {
            background: #2ecc71;
            color: white;
        }

        .add-to-cart:hover {
            background: #27ae60;
        }

        .add-to-wishlist {
            background: #e74c3c;
            color: white;
        }

        .add-to-wishlist:hover {
            background: #c0392b;
        }

        .success-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            background: #2ecc71;
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: none;
            z-index: 1000;
        }

        .image-preview-container {
            display: none;
            gap: 10px;
            margin-bottom: 20px;
        }

        .image-preview-container.active {
            display: flex;
        }

        .selected-image-preview {
            width: 200px;
            height: 200px;
            border-radius: 8px;
            overflow: hidden;
            display: none;
        }

        .selected-image-preview.active {
            display: block;
        }

        .selected-image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumbnail-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .thumbnail {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .thumbnail:hover {
            border-color: #3498db;
        }

        .thumbnail.active {
            border-color: #2ecc71;
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #ddd;
            background: #f8f9fa;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-input {
            width: 50px;
            height: 30px;
            text-align: center;
            margin: 0 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .checkout-btn {
            width: 100%;
            padding: 12px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 20px;
        }

        .checkout-btn:hover {
            background: #27ae60;
        }

        @media (max-width: 768px) {
            .product-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="product-details">
        <div class="image-gallery">
            <?php if (!in_array($product_id, [1, 2, 3])): ?>
            <div class="gallery-image">
                <img src="<?php echo htmlspecialchars($product['image_url'] ?? $product['image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?> - Main View"
                     onerror="this.src='images/placeholder.jpg'">
            </div>
            <?php endif; ?>
            <?php 
            $additional_prices = [
                1 => [2.99, 3.99, 3.99, 4.99], // Color Blocks prices
                2 => [3.99, 4.99, 4.99, 4.99], // Alphabet Puzzle prices
                3 => [2.99, 2.99, 3.99, 3.99]  // Number Match prices
            ];
            
            foreach ($product_images as $index => $image): 
                $price = isset($additional_prices[$product_id][$index]) ? $additional_prices[$product_id][$index] : $product['price'];
            ?>
            <div class="gallery-image" data-price="<?php echo $price; ?>" onclick="selectImage(this, <?php echo $price; ?>)">
                <img src="<?php echo htmlspecialchars($image); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?> - View <?php echo $index + 2; ?>"
                     onerror="this.src='images/placeholder.jpg'">
                <div class="image-price">$<?php echo number_format($price, 2); ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="product-info">
            <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
            <div class="product-price" id="selected-price">$<?php echo number_format($product['price'], 2); ?></div>
            <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
            
            <div class="image-preview-container" id="imagePreviewContainer">
                <div class="selected-image-preview" id="selectedImagePreview">
                    <img src="" alt="Selected Image">
                </div>
                <div class="thumbnail-container">
                    <?php foreach ($product_images as $index => $image): 
                        $price = isset($additional_prices[$product_id][$index]) ? $additional_prices[$product_id][$index] : $product['price'];
                    ?>
                    <div class="thumbnail" onclick="selectImage(this, <?php echo $price; ?>)">
                        <img src="<?php echo htmlspecialchars($image); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?> - View <?php echo $index + 2; ?>"
                             onerror="this.src='images/placeholder.jpg'">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="quantity-control">
                <button class="quantity-btn" onclick="updateQuantity(-1)">-</button>
                <input type="number" class="quantity-input" id="quantityInput" value="1" min="1" onchange="updateTotalPrice()">
                <button class="quantity-btn" onclick="updateQuantity(1)">+</button>
            </div>
            
            <div class="action-buttons">
                <button class="action-btn add-to-cart" onclick="addToCart(<?php echo $product['id']; ?>, getSelectedPrice(), getQuantity())">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
                <button class="action-btn add-to-wishlist" onclick="addToWishlist(<?php echo $product['id']; ?>, getSelectedPrice(), getQuantity())">
                    <i class="fas fa-heart"></i> Add to Wishlist
                </button>
            </div>

            <button class="checkout-btn" onclick="proceedToCheckout()">
                <i class="fas fa-shopping-bag"></i> Proceed to Checkout
            </button>
        </div>
    </div>

    <div id="successMessage" class="success-message"></div>

    <script>
        let selectedImage = null;
        let selectedPrice = <?php echo $product['price']; ?>;
        let quantity = 1;

        function selectImage(element, price) {
            // Show the image preview container if it's hidden
            document.getElementById('imagePreviewContainer').classList.add('active');
            
            // Remove selected class from all thumbnails
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            
            // Add selected class to clicked thumbnail
            element.classList.add('active');
            selectedImage = element;
            selectedPrice = price;
            
            // Update displayed price
            document.getElementById('selected-price').textContent = '$' + price.toFixed(2);
            
            // Update selected image preview
            const selectedImagePreview = document.getElementById('selectedImagePreview');
            const previewImg = selectedImagePreview.querySelector('img');
            
            previewImg.src = element.querySelector('img').src;
            selectedImagePreview.classList.add('active');
            
            updateTotalPrice();
        }

        function updateQuantity(change) {
            const quantityInput = document.getElementById('quantityInput');
            let newQuantity = parseInt(quantityInput.value) + change;
            if (newQuantity < 1) newQuantity = 1;
            quantityInput.value = newQuantity;
            quantity = newQuantity;
            updateTotalPrice();
        }

        function updateTotalPrice() {
            const quantityInput = document.getElementById('quantityInput');
            quantity = parseInt(quantityInput.value);
            const totalPrice = selectedPrice * quantity;
            document.getElementById('selected-price').textContent = '$' + totalPrice.toFixed(2);
        }

        function getQuantity() {
            return parseInt(document.getElementById('quantityInput').value);
        }

        function getSelectedPrice() {
            return selectedPrice;
        }

        function addToCart(productId, price, quantity) {
            if (!selectedImage && in_array(<?php echo $product_id; ?>, [1, 2, 3])) {
                alert('Please select an image variant first');
                return;
            }

            const data = {
                product_id: productId,
                price: price,
                quantity: quantity,
                image_url: selectedImage ? selectedImage.querySelector('img').src : '<?php echo $product['image_url'] ?? $product['image']; ?>'
            };

            fetch('ajax/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showMessage('Product added to cart successfully!');
                    // Update cart count in header
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count;
                    } else {
                        // Create cart count if it doesn't exist
                        const cartLink = document.querySelector('a[href*="cart.php"]');
                        if (cartLink) {
                            const countSpan = document.createElement('span');
                            countSpan.className = 'cart-count';
                            countSpan.style.cssText = 'position: absolute; top: -8px; right: -8px; background: #e53935; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px;';
                            countSpan.textContent = data.cart_count;
                            cartLink.appendChild(countSpan);
                        }
                    }
                } else {
                    if (data.message === 'Please login to add items to cart') {
                        window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
                    } else {
                        showMessage(data.message || 'Error adding product to cart');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Error adding product to cart. Please try again.');
            });
        }

        function addToWishlist(productId, price, quantity) {
            if (!selectedImage && in_array(<?php echo $product_id; ?>, [1, 2, 3])) {
                alert('Please select an image variant first');
                return;
            }

            const data = {
                product_id: productId,
                price: price,
                image_url: selectedImage ? selectedImage.querySelector('img').src : '<?php echo $product['image_url'] ?? $product['image']; ?>'
            };

            fetch('ajax/add_to_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showMessage('Product added to wishlist successfully!');
                } else {
                    if (data.message === 'Please login to add items to wishlist') {
                        window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
                    } else {
                        showMessage(data.message || 'Error adding product to wishlist');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Error adding product to wishlist. Please try again.');
            });
        }

        function proceedToCheckout() {
            if (!selectedImage && in_array(<?php echo $product_id; ?>, [1, 2, 3])) {
                alert('Please select an image variant first');
                return;
            }

            const productData = {
                product_id: <?php echo $product['id']; ?>,
                price: selectedPrice,
                quantity: getQuantity(),
                image: selectedImage ? selectedImage.querySelector('img').src : '<?php echo $product['image_url'] ?? $product['image']; ?>'
            };

            // Store in sessionStorage for checkout page
            sessionStorage.setItem('checkoutItems', JSON.stringify([productData]));
            
            // Redirect to checkout
            window.location.href = 'checkout.php';
        }

        function in_array(needle, haystack) {
            return haystack.indexOf(needle) !== -1;
        }

        function showMessage(message) {
            const messageElement = document.getElementById('successMessage');
            messageElement.textContent = message;
            messageElement.style.display = 'block';
            setTimeout(() => {
                messageElement.style.display = 'none';
            }, 3000);
        }
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 