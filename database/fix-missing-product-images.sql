-- Point the four broken product images at clean filenames under assets/images/artwork/
-- Run on production after uploading the new image files.

SET NAMES utf8mb4;

UPDATE products
SET image = 'images/artwork/red-indian-spirit-of-warrior.jpeg',
    image_alt = 'Red Indian — Spirit of Warrior, artwork by Lakshmi Sridhar'
WHERE id = 'morning-light';

UPDATE products
SET image = 'images/artwork/the-beauty-of-a-rose.jpeg',
    image_alt = 'The Beauty of a Rose — colour pencil artwork by Lakshmi Sridhar'
WHERE id = 'the-beauty-of-a-rose';

UPDATE products
SET image = 'images/artwork/patterns-of-tradition-kolam.jpeg',
    image_alt = 'Patterns of Tradition — The Art of Kolam, artwork by Lakshmi Sridhar'
WHERE id = 'patterns-of-tradition-the-art-of-kolam';

UPDATE products
SET image = 'images/artwork/crown-of-thorns.jpeg',
    image_alt = 'The Saviour''s Gaze — graphite study by Lakshmi Sridhar'
WHERE id = 'marigold-garden';
