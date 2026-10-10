-- Seed Recent Projects with the previous default grid (featured first, up to 6).
-- Safe to run if recent_project_items is empty. Skips when rows already exist.
-- Run once in phpMyAdmin after admin-galleries.sql.

SET NAMES utf8mb4;

INSERT INTO recent_project_items (product_id, sort_order, is_bestseller)
SELECT p.id, p.sort_order, p.featured
FROM products p
WHERE p.status = 'available'
  AND p.featured = 1
  AND NOT EXISTS (SELECT 1 FROM recent_project_items LIMIT 1)
ORDER BY p.sort_order ASC, p.title ASC
LIMIT 6;

-- If still empty (no featured products), seed any available products
INSERT INTO recent_project_items (product_id, sort_order, is_bestseller)
SELECT p.id, p.sort_order, 0
FROM products p
WHERE p.status = 'available'
  AND NOT EXISTS (SELECT 1 FROM recent_project_items LIMIT 1)
ORDER BY p.sort_order ASC, p.title ASC
LIMIT 6;
