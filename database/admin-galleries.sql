-- Admin galleries: Student work (Courses) + Recent Projects picks
-- Run once in phpMyAdmin → SQL after purchase-redesign.sql

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS student_work (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  image VARCHAR(255) NOT NULL,
  image_alt VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_student_work_sort (sort_order),
  KEY idx_student_work_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS recent_project_items (
  product_id VARCHAR(64) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_bestseller TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (product_id),
  KEY idx_recent_sort (sort_order),
  CONSTRAINT fk_recent_product
    FOREIGN KEY (product_id) REFERENCES products (id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed student work from the existing classroom image folder (skip if already seeded)
INSERT INTO student_work (image, image_alt, sort_order, is_active)
SELECT v.image, v.image_alt, v.sort_order, 1
FROM (
  SELECT 'images/artwork/students-christmas-cards-2026/apple.jpeg' AS image, 'Apple — student artwork from Lakshmi’s classes' AS image_alt, 10 AS sort_order
  UNION ALL SELECT 'images/artwork/students-christmas-cards-2026/butterfly.jpeg', 'Butterfly — student artwork from Lakshmi’s classes', 20
  UNION ALL SELECT 'images/artwork/students-christmas-cards-2026/cindrella.jpeg', 'Cinderella — student artwork from Lakshmi’s classes', 30
  UNION ALL SELECT 'images/artwork/students-christmas-cards-2026/eating.jpeg', 'Eating — student artwork from Lakshmi’s classes', 40
  UNION ALL SELECT 'images/artwork/students-christmas-cards-2026/fruits.jpeg', 'Fruits — student artwork from Lakshmi’s classes', 50
  UNION ALL SELECT 'images/artwork/students-christmas-cards-2026/ganpati-1.jpeg', 'Ganpati — student artwork from Lakshmi’s classes', 60
  UNION ALL SELECT 'images/artwork/students-christmas-cards-2026/ganpati-2.jpeg', 'Ganpati — student artwork from Lakshmi’s classes', 70
  UNION ALL SELECT 'images/artwork/students-christmas-cards-2026/hanuman.jpeg', 'Hanuman — student artwork from Lakshmi’s classes', 80
  UNION ALL SELECT 'images/artwork/students-christmas-cards-2026/parrot-1.jpeg', 'Parrot — student artwork from Lakshmi’s classes', 90
  UNION ALL SELECT 'images/artwork/students-christmas-cards-2026/parrot-2.jpeg', 'Parrot — student artwork from Lakshmi’s classes', 100
  UNION ALL SELECT 'images/artwork/students-christmas-cards-2026/parrot-3.jpeg', 'Parrot — student artwork from Lakshmi’s classes', 110
  UNION ALL SELECT 'images/artwork/students-christmas-cards-2026/scenery-1.jpeg', 'Scenery — student artwork from Lakshmi’s classes', 120
  UNION ALL SELECT 'images/artwork/students-christmas-cards-2026/scenery-2.jpeg', 'Scenery — student artwork from Lakshmi’s classes', 130
  UNION ALL SELECT 'images/artwork/students-christmas-cards-2026/scenery-3.jpeg', 'Scenery — student artwork from Lakshmi’s classes', 140
  UNION ALL SELECT 'images/artwork/students-christmas-cards-2026/scenery-4.jpeg', 'Scenery — student artwork from Lakshmi’s classes', 150
  UNION ALL SELECT 'images/artwork/students-christmas-cards-2026/woman.jpeg', 'Woman — student artwork from Lakshmi’s classes', 160
) AS v
WHERE NOT EXISTS (SELECT 1 FROM student_work LIMIT 1);

-- Recent projects start empty — pick them in Admin → Recent projects.
-- (Featured on home stays on each product’s edit form.)
