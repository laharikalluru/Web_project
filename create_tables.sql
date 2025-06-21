-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    age_range VARCHAR(50),
    image_url VARCHAR(255),
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create product_images table
CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert sample products
INSERT INTO products (name, description, price, age_range, image_url, category) VALUES
('Shape Sorter', 'A colorful educational toy that helps children learn about different shapes and colors.', 22.99, '1-3', 'images/toys/shape-sorter.jpg', 'Educational'),
('Dino Buddy', 'A soft and cuddly dinosaur plush toy perfect for bedtime.', 15.99, '0-3', 'images/toys/dino-buddy.jpg', 'Plush');

-- Insert additional images for Shape Sorter
INSERT INTO product_images (product_id, image_url) VALUES
(1, 'images/toys/shape-sorter-2.jpg'),
(1, 'images/toys/shape-sorter-3.jpg'),
(1, 'images/toys/shape-sorter-4.jpg');

-- Insert additional images for Dino Buddy
INSERT INTO product_images (product_id, image_url) VALUES
(2, 'images/toys/dino-buddy-2.jpg'),
(2, 'images/toys/dino-buddy-3.jpg'),
(2, 'images/toys/dino-buddy-4.jpg'); 