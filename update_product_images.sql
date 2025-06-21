-- First, clear existing product images
DELETE FROM product_images;

-- Reset auto increment
ALTER TABLE product_images AUTO_INCREMENT = 1;

-- Insert images for Color Blocks (product_id = 1)
INSERT INTO product_images (product_id, image_url) VALUES
(1, 'images/images/toys/color-blocks-2.jpg'),
(1, 'images/images/toys/color-blocks-3.jpg'),
(1, 'images/images/toys/color-blocks-4.jpg'),
(1, 'images/images/toys/color-blocks-5.jpg');

-- Insert images for Alphabet Puzzle (product_id = 2)
INSERT INTO product_images (product_id, image_url) VALUES
(2, 'images/images/toys/alphabet-puzzle-2.jpg'),
(2, 'images/images/toys/alphabet-puzzle-3.jpg'),
(2, 'images/images/toys/alphabet-puzzle-4.jpg'),
(2, 'images/images/toys/alphabet-puzzle-5.jpg');

-- Insert images for Number Match (product_id = 3)
INSERT INTO product_images (product_id, image_url) VALUES
(3, 'images/images/toys/number-match-2.jpg'),
(3, 'images/images/toys/number-match-3.jpg'),
(3, 'images/images/toys/number-match-4.jpg'),
(3, 'images/images/toys/number-match-5.jpg'); 