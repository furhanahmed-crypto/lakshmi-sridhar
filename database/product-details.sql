-- Shared product-details copy (used on every product page).
-- Run in phpMyAdmin → your database → SQL tab.
-- If you already created product_detail_sections, run this instead:
--   RENAME TABLE product_detail_sections TO product_common_details;
-- Add or edit the actual copy in /admin/details.php — do not hardcode it in PHP.

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS product_common_details (
  id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  heading VARCHAR(160) NOT NULL,
  body TEXT NOT NULL,
  sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
