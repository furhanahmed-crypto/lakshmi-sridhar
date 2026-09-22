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
