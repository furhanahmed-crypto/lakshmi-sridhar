-- Per-product additional details (replaces product_common_details).
-- Run once in phpMyAdmin → SQL. Then edit any product in /admin/edit.php.

SET NAMES utf8mb4;

ALTER TABLE products
  ADD COLUMN product_additional_details TEXT NULL AFTER description;

UPDATE products SET product_additional_details = '[{"title":"Size and quality","body":"Dimension: 10 inch × 13 inch. Print quality: the artwork is printed on 300 GSM thick paper with a high quality printer and vibrant colours, to give it a rich look. Item shape: rectangular. Frame material: engineered wood."},{"title":"Great for gifting","body":"These framed posters encourage everyone to live a positive life and achieve more. Their longevity and everyday use give them something to remember you by. A thoughtful gift for a girl, man, boy, student, brother, or friend — and a perfect present for loved ones, colleagues, and friends."},{"title":"Reusable frames","body":"If you want to change the artwork for another poster or photo later, you can. Remove the MDF wood board and put in a new image of your choice."},{"title":"Use wherever you want","body":"These stylish picture frames work as home and office decoration, and also suit hostels, study rooms, classrooms, corridors, shops, and cafés. If you can find a wall to hang them on, they will stay and say something."}]';

DROP TABLE IF EXISTS product_common_details;
DROP TABLE IF EXISTS product_detail_sections;
