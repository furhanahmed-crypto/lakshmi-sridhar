-- Update the original shop products (image-1, image-2, …) to match the artwork.
-- Also points each row at the renamed image file.
-- Product ids stay the same so existing URLs keep working.
-- Run once in phpMyAdmin.

SET NAMES utf8mb4;

UPDATE products SET
  title = 'The Reader',
  description = 'A white figure seated with an open book, chin resting in the hand — a quiet study of thought.',
  image = 'images/artwork/thinker-reading.jpeg',
  image_alt = 'The Reader — artwork by Lakshmi Sridhar'
WHERE id = 'morning-light';

UPDATE products SET
  title = 'Krishna',
  description = 'Lord Krishna with flute, jasmine, and falling red petals, drawn in colour on a field of gold.',
  image = 'images/artwork/krishna-petals.jpeg',
  image_alt = 'Krishna — artwork by Lakshmi Sridhar'
WHERE id = 'harbour-memory';

UPDATE products SET
  title = 'Crown of Thorns',
  description = 'A graphite study of Christ, eyes closed, wearing the crown of thorns.',
  image = 'images/artwork/crown-of-thorns.jpeg',
  image_alt = 'Crown of Thorns — artwork by Lakshmi Sridhar'
WHERE id = 'marigold-garden';

UPDATE products SET
  title = 'Two Women',
  description = 'Two women in traditional jewellery and jasmine — one in profile, one seen from behind.',
  image = 'images/artwork/two-women.jpeg',
  image_alt = 'Two Women — artwork by Lakshmi Sridhar'
WHERE id = 'quiet-room';

UPDATE products SET
  title = 'The Pug',
  description = 'A colour-pencil portrait of a pug, tongue out, drawn with the softness of fur and the shine of a wet nose.',
  image = 'images/artwork/the-pug.jpeg',
  image_alt = 'The Pug — artwork by Lakshmi Sridhar'
WHERE id = 'river-stones';

UPDATE products SET
  title = 'Krishna with Flute',
  description = 'A graphite study of Krishna, flute in hand, crown and birds drawn in careful light.',
  image = 'images/artwork/krishna-flute.jpeg',
  image_alt = 'Krishna with Flute — artwork by Lakshmi Sridhar'
WHERE id = 'ember-dusk';

UPDATE products SET
  title = 'Two Women',
  description = 'Two women in traditional jewellery and jasmine — one in profile, one seen from behind.',
  image = 'images/artwork/two-women-study.jpeg',
  image_alt = 'Two Women — artwork by Lakshmi Sridhar'
WHERE id = 'studio-piece-07';

UPDATE products SET
  title = 'The Warrior',
  description = 'A colour-pencil portrait of a man in red face paint and a single braid.',
  image = 'images/artwork/the-warrior.jpeg',
  image_alt = 'The Warrior — artwork by Lakshmi Sridhar'
WHERE id = 'studio-piece-08';

UPDATE products SET
  title = 'Red Apples',
  description = 'A close still life of red apples — water on the skin, leaves still attached.',
  image = 'images/artwork/red-apples.jpeg',
  image_alt = 'Red Apples — artwork by Lakshmi Sridhar'
WHERE id = 'studio-piece-09';

UPDATE products SET
  title = 'The Leopard',
  description = 'A leopard in profile, spots and whiskers drawn in graphite on a warm ground.',
  image = 'images/artwork/the-leopard.jpeg',
  image_alt = 'The Leopard — artwork by Lakshmi Sridhar'
WHERE id = 'studio-piece-10';

UPDATE products SET
  title = 'Drawing Kolam',
  description = 'A woman in a yellow sari drawing a kolam, seen from above — white lines finding their pattern.',
  image = 'images/artwork/drawing-kolam.jpeg',
  image_alt = 'Drawing Kolam — artwork by Lakshmi Sridhar'
WHERE id = 'studio-piece-11';
