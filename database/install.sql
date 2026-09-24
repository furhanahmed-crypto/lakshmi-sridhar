-- Lakshmi Sridhar — products schema
-- Run this first in phpMyAdmin (SQL tab) on database: u531995345_lakshmi

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE TABLE IF NOT EXISTS products (
  id VARCHAR(64) NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT NULL,
  image VARCHAR(255) NOT NULL,
  image_alt VARCHAR(255) NULL,
  tags JSON NOT NULL,
  status ENUM('available', 'sold') NOT NULL DEFAULT 'available',
  featured TINYINT(1) NOT NULL DEFAULT 0,
  default_size CHAR(1) NOT NULL DEFAULT 'S',
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_products_status (status),
  KEY idx_products_featured (featured),
  KEY idx_products_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS product_sizes (
  product_id VARCHAR(64) NOT NULL,
  size_code CHAR(1) NOT NULL,
  label VARCHAR(16) NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  compare_at DECIMAL(10, 2) NULL,
  PRIMARY KEY (product_id, size_code),
  CONSTRAINT fk_product_sizes_product
    FOREIGN KEY (product_id) REFERENCES products (id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Seed products from productsData.php
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE product_sizes;
TRUNCATE TABLE products;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('morning-light', 'The Reader', 'A white figure seated with an open book, chin resting in the hand — a quiet study of thought.', 'images/artwork/thinker-reading.jpeg', 'The Reader — artwork by Lakshmi Sridhar', '["original", "print"]', 'available', 1, 'S', 0);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('morning-light', 'S', '13"', 89.00, 129.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('morning-light', 'M', '15"', 119.00, 159.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('morning-light', 'L', '20"', 149.00, 199.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('harbour-memory', 'Krishna', 'Lord Krishna with flute, jasmine, and falling red petals, drawn in colour on a field of gold.', 'images/artwork/krishna-petals.jpeg', 'Krishna — artwork by Lakshmi Sridhar', '["original", "print"]', 'available', 1, 'S', 1);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('harbour-memory', 'S', '13"', 89.00, 129.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('harbour-memory', 'M', '15"', 119.00, 159.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('harbour-memory', 'L', '20"', 149.00, 199.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('marigold-garden', 'Crown of Thorns', 'A graphite study of Christ, eyes closed, wearing the crown of thorns.', 'images/artwork/crown-of-thorns.jpeg', 'Crown of Thorns — artwork by Lakshmi Sridhar', '["original", "print"]', 'available', 1, 'S', 2);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('marigold-garden', 'S', '13"', 95.00, 135.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('marigold-garden', 'M', '15"', 125.00, 165.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('marigold-garden', 'L', '20"', 155.00, 205.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('quiet-room', 'Two Women', 'Two women in traditional jewellery and jasmine — one in profile, one seen from behind.', 'images/artwork/two-women.jpeg', 'Two Women — artwork by Lakshmi Sridhar', '["original", "print"]', 'available', 0, 'S', 3);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('quiet-room', 'S', '13"', 89.00, 129.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('quiet-room', 'M', '15"', 119.00, 159.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('quiet-room', 'L', '20"', 149.00, 199.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('river-stones', 'The Pug', 'A colour-pencil portrait of a pug, tongue out, drawn with the softness of fur and the shine of a wet nose.', 'images/artwork/the-pug.jpeg', 'The Pug — artwork by Lakshmi Sridhar', '["print"]', 'available', 1, 'S', 4);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('river-stones', 'S', '13"', 79.00, 119.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('river-stones', 'M', '15"', 109.00, 149.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('river-stones', 'L', '20"', 139.00, 189.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('ember-dusk', 'Krishna with Flute', 'A graphite study of Krishna, flute in hand, crown and birds drawn in careful light.', 'images/artwork/krishna-flute.jpeg', 'Krishna with Flute — artwork by Lakshmi Sridhar', '["original"]', 'available', 0, 'S', 5);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('ember-dusk', 'S', '13"', 99.00, 139.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('ember-dusk', 'M', '15"', 129.00, 169.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('ember-dusk', 'L', '20"', 159.00, 209.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('studio-piece-07', 'Two Women', 'Two women in traditional jewellery and jasmine — one in profile, one seen from behind.', 'images/artwork/two-women-study.jpeg', 'Two Women — artwork by Lakshmi Sridhar', '["original", "print"]', 'available', 0, 'S', 6);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-07', 'S', '13"', 89.00, 129.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-07', 'M', '15"', 119.00, 159.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-07', 'L', '20"', 149.00, 199.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('studio-piece-08', 'The Warrior', 'A colour-pencil portrait of a man in red face paint and a single braid.', 'images/artwork/the-warrior.jpeg', 'The Warrior — artwork by Lakshmi Sridhar', '["original", "print"]', 'available', 0, 'S', 7);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-08', 'S', '13"', 89.00, 129.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-08', 'M', '15"', 119.00, 159.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-08', 'L', '20"', 149.00, 199.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('studio-piece-09', 'Red Apples', 'A close still life of red apples — water on the skin, leaves still attached.', 'images/artwork/red-apples.jpeg', 'Red Apples — artwork by Lakshmi Sridhar', '["original", "print"]', 'available', 0, 'S', 8);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-09', 'S', '13"', 89.00, 129.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-09', 'M', '15"', 119.00, 159.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-09', 'L', '20"', 149.00, 199.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('studio-piece-10', 'The Leopard', 'A leopard in profile, spots and whiskers drawn in graphite on a warm ground.', 'images/artwork/the-leopard.jpeg', 'The Leopard — artwork by Lakshmi Sridhar', '["original"]', 'available', 0, 'S', 9);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-10', 'S', '13"', 99.00, 139.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-10', 'M', '15"', 129.00, 169.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-10', 'L', '20"', 159.00, 209.00);

INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('studio-piece-11', 'Drawing Kolam', 'A woman in a yellow sari drawing a kolam, seen from above — white lines finding their pattern.', 'images/artwork/drawing-kolam.jpeg', 'Drawing Kolam — artwork by Lakshmi Sridhar', '["print"]', 'available', 0, 'S', 10);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-11', 'S', '13"', 79.00, 119.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-11', 'M', '15"', 109.00, 149.00);
INSERT INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('studio-piece-11', 'L', '20"', 139.00, 189.00);
