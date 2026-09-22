-- Seed products from productsData.php
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE product_sizes;
TRUNCATE TABLE products;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('morning-light', 'Morning Light', 'Soft colour studies from quiet mornings in the studio, layered until the light felt true.', 'images/artwork/image-1.jpeg', 'Morning Light — artwork by Lakshmi Sridhar', '["original", "print"]', 'available', 1, 'S', 0);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('morning-light', 'S', '13"', 89.00, 129.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('morning-light', 'M', '15"', 119.00, 159.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('morning-light', 'L', '20"', 149.00, 199.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('harbour-memory', 'Harbour Memory', 'Drawn from walks along the Irish coast — salt air, shifting greys, and the warmth of colour that remains when the weather clears.', 'images/artwork/image-2.jpeg', 'Harbour Memory — artwork by Lakshmi Sridhar', '["original", "print"]', 'available', 1, 'S', 1);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('harbour-memory', 'S', '13"', 89.00, 129.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('harbour-memory', 'M', '15"', 119.00, 159.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('harbour-memory', 'L', '20"', 149.00, 199.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('marigold-garden', 'Marigold Garden', 'Inspired by gardens of memory — marigold golds, deep greens, and the quiet joy of watching colour bloom.', 'images/artwork/image-3.jpeg', 'Marigold Garden — artwork by Lakshmi Sridhar', '["original", "print"]', 'available', 1, 'S', 2);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('marigold-garden', 'S', '13"', 95.00, 135.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('marigold-garden', 'M', '15"', 125.00, 165.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('marigold-garden', 'L', '20"', 155.00, 205.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('quiet-room', 'Quiet Room', 'An interior study of soft shadows and warm neutrals — the kind of light that settles into a room in late afternoon.', 'images/artwork/image-4.jpeg', 'Quiet Room — artwork by Lakshmi Sridhar', '["original", "print"]', 'available', 0, 'S', 3);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('quiet-room', 'S', '13"', 89.00, 129.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('quiet-room', 'M', '15"', 119.00, 159.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('quiet-room', 'L', '20"', 149.00, 199.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('river-stones', 'River Stones', 'Smooth forms and muted earth tones — pebbles, water, and the patience of looking closely.', 'images/artwork/image-5.jpeg', 'River Stones — artwork by Lakshmi Sridhar', '["print"]', 'available', 1, 'S', 4);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('river-stones', 'S', '13"', 79.00, 119.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('river-stones', 'M', '15"', 109.00, 149.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('river-stones', 'L', '20"', 139.00, 189.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('ember-dusk', 'Ember Dusk', 'Warm peanut and slate tones at the edge of evening — a sky that holds onto colour just a little longer.', 'images/artwork/image-6.jpeg', 'Ember Dusk — artwork by Lakshmi Sridhar', '["original"]', 'available', 0, 'S', 5);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('ember-dusk', 'S', '13"', 99.00, 139.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('ember-dusk', 'M', '15"', 129.00, 169.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('ember-dusk', 'L', '20"', 159.00, 209.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('studio-piece-07', 'Studio Piece 07', 'A recent studio piece — layered slowly, colour by colour.', 'images/artwork/image-17.jpeg', 'Studio Piece 07 — artwork by Lakshmi Sridhar', '["original", "print"]', 'available', 0, 'S', 6);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-07', 'S', '13"', 89.00, 129.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-07', 'M', '15"', 119.00, 159.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-07', 'L', '20"', 149.00, 199.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('studio-piece-08', 'Studio Piece 08', 'A recent studio piece — layered slowly, colour by colour.', 'images/artwork/image-18.jpeg', 'Studio Piece 08 — artwork by Lakshmi Sridhar', '["original", "print"]', 'available', 0, 'S', 7);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-08', 'S', '13"', 89.00, 129.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-08', 'M', '15"', 119.00, 159.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-08', 'L', '20"', 149.00, 199.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('studio-piece-09', 'Studio Piece 09', 'A recent studio piece — layered slowly, colour by colour.', 'images/artwork/image-19.jpeg', 'Studio Piece 09 — artwork by Lakshmi Sridhar', '["original", "print"]', 'available', 0, 'S', 8);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-09', 'S', '13"', 89.00, 129.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-09', 'M', '15"', 119.00, 159.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-09', 'L', '20"', 149.00, 199.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('studio-piece-10', 'Studio Piece 10', 'A recent studio piece — layered slowly, colour by colour.', 'images/artwork/image-20.jpeg', 'Studio Piece 10 — artwork by Lakshmi Sridhar', '["original"]', 'available', 0, 'S', 9);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-10', 'S', '13"', 99.00, 139.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-10', 'M', '15"', 129.00, 169.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-10', 'L', '20"', 159.00, 209.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('studio-piece-11', 'Studio Piece 11', 'A recent studio piece — layered slowly, colour by colour.', 'images/artwork/image-21.jpeg', 'Studio Piece 11 — artwork by Lakshmi Sridhar', '["print"]', 'available', 0, 'S', 10);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-11', 'S', '13"', 79.00, 119.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-11', 'M', '15"', 109.00, 149.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-11', 'L', '20"', 139.00, 189.00);
