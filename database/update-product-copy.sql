-- Update product titles, descriptions, and alt text to match the artwork.
-- Run once in phpMyAdmin. Product ids stay the same.

SET NAMES utf8mb4;

-- Animals
UPDATE products SET
  title = 'The Pug',
  description = 'A colour-pencil portrait of a pug, tongue out, drawn with the softness of fur and the shine of a wet nose.',
  image_alt = 'The Pug — artwork by Lakshmi Sridhar'
WHERE id = 'animals-dog-1';

UPDATE products SET
  title = 'German Shepherd',
  description = 'A German shepherd holding a braided rope toy — alert, proud, and ready to play.',
  image_alt = 'German Shepherd — artwork by Lakshmi Sridhar'
WHERE id = 'animals-dog-2';

UPDATE products SET
  title = 'Mother and Calf',
  description = 'A graphite study of an elephant and her calf, drawn quietly on a dark ground.',
  image_alt = 'Mother and Calf — artwork by Lakshmi Sridhar'
WHERE id = 'animals-elephant';

UPDATE products SET
  title = 'Number 2477',
  description = 'A thoroughbred in bridle and race number, drawn in colour pencil against a quiet white field.',
  image_alt = 'Number 2477 — artwork by Lakshmi Sridhar'
WHERE id = 'animals-horse';

UPDATE products SET
  title = 'The Lion',
  description = 'A lion in profile, mane drawn hair by hair in warm golds and umbers.',
  image_alt = 'The Lion — artwork by Lakshmi Sridhar'
WHERE id = 'animals-lion';

UPDATE products SET
  title = 'Blue-and-Gold Macaw',
  description = 'A macaw study in cobalt, gold, and green, looking back over the shoulder.',
  image_alt = 'Blue-and-Gold Macaw — artwork by Lakshmi Sridhar'
WHERE id = 'animals-parrot-1';

UPDATE products SET
  title = 'Scarlet Macaw',
  description = 'A scarlet macaw on a perch — red, green, and blue layered slowly in colour pencil.',
  image_alt = 'Scarlet Macaw — artwork by Lakshmi Sridhar'
WHERE id = 'animals-parrot-2';

UPDATE products SET
  title = 'The Leopard',
  description = 'A leopard in profile, spots and whiskers drawn in graphite on a warm ground.',
  image_alt = 'The Leopard — artwork by Lakshmi Sridhar'
WHERE id = 'animals-tiger';

UPDATE products SET
  title = 'Lion Family',
  description = 'A lion, lioness, and cub asleep together, drawn in soft colour pencil.',
  image_alt = 'Lion Family — artwork by Lakshmi Sridhar'
WHERE id = 'animals-tigers-family';

-- Still life
UPDATE products SET
  title = 'Red Apples',
  description = 'A close still life of red apples — water on the skin, leaves still attached.',
  image_alt = 'Red Apples — artwork by Lakshmi Sridhar'
WHERE id = 'still-life-apples';

UPDATE products SET
  title = 'On the Rocks',
  description = 'Whisky poured over ice — glass, liquid, and light drawn in colour pencil.',
  image_alt = 'On the Rocks — artwork by Lakshmi Sridhar'
WHERE id = 'still-life-bear';

UPDATE products SET
  title = 'Autumn Grapes',
  description = 'Dark grapes in a silver bowl, with maple leaves turning red.',
  image_alt = 'Autumn Grapes — artwork by Lakshmi Sridhar'
WHERE id = 'still-life-grapes';

UPDATE products SET
  title = 'Bowl of Limes',
  description = 'Limes in a blue-and-white bowl, one cut open beside the knife.',
  image_alt = 'Bowl of Limes — artwork by Lakshmi Sridhar'
WHERE id = 'still-life-lemon';

-- Portraits
UPDATE products SET
  title = 'The Actress',
  description = 'A graphite portrait of a young woman — necklace, dark hair, and a quiet smile.',
  image_alt = 'The Actress — artwork by Lakshmi Sridhar'
WHERE id = 'portraits-actress';

UPDATE products SET
  title = 'Crown of Thorns',
  description = 'A graphite study of Christ, eyes closed, wearing the crown of thorns.',
  image_alt = 'Crown of Thorns — artwork by Lakshmi Sridhar'
WHERE id = 'portraits-old-man-1';

UPDATE products SET
  title = 'The Elder',
  description = 'A graphite portrait of an older man in a head wrap, every line of the face held.',
  image_alt = 'The Elder — artwork by Lakshmi Sridhar'
WHERE id = 'portraits-old-man-2';

UPDATE products SET
  title = 'The Film Star',
  description = 'A graphite portrait of a South Indian film star, looking back with a smile.',
  image_alt = 'The Film Star — artwork by Lakshmi Sridhar'
WHERE id = 'portraits-south-filmstar';

UPDATE products SET
  title = 'The Warrior',
  description = 'A colour-pencil portrait of a man in red face paint and a single braid.',
  image_alt = 'The Warrior — artwork by Lakshmi Sridhar'
WHERE id = 'portraits-tribal-man';

UPDATE products SET
  title = 'The Dancer',
  description = 'A Bharatanatyam dancer in jewellery and mudra, drawn in graphite.',
  image_alt = 'The Dancer — artwork by Lakshmi Sridhar'
WHERE id = 'portraits-woman-dance';

UPDATE products SET
  title = 'Two Women',
  description = 'Two women in traditional jewellery and jasmine — one in profile, one seen from behind.',
  image_alt = 'Two Women — artwork by Lakshmi Sridhar'
WHERE id = 'portraits-women';

-- Students' Christmas cards, 2026
UPDATE products SET
  title = 'The Apple',
  description = 'A student close-up of a red apple with dew, from the 2026 Christmas cards.',
  image_alt = 'The Apple — student Christmas card, 2026'
WHERE id = 'students-christmas-apple';

UPDATE products SET
  title = 'Monarch',
  description = 'A student study of a butterfly on orange blossom, from the 2026 Christmas cards.',
  image_alt = 'Monarch — student Christmas card, 2026'
WHERE id = 'students-christmas-butterfly';

UPDATE products SET
  title = 'Snow White',
  description = 'A student drawing of Snow White in the woods with birds and a fawn, from the 2026 Christmas cards.',
  image_alt = 'Snow White — student Christmas card, 2026'
WHERE id = 'students-christmas-cindrella';

UPDATE products SET
  title = 'Cherry',
  description = 'A student study of a cherry held between the lips, from the 2026 Christmas cards.',
  image_alt = 'Cherry — student Christmas card, 2026'
WHERE id = 'students-christmas-eating';

UPDATE products SET
  title = 'Fruit and Vase',
  description = 'A student still life of fruit, grapes, and a blue vase, from the 2026 Christmas cards.',
  image_alt = 'Fruit and Vase — student Christmas card, 2026'
WHERE id = 'students-christmas-fruits';

UPDATE products SET
  title = 'Baby Ganesh',
  description = 'A student drawing of baby Ganesh on a swing, from the 2026 Christmas cards.',
  image_alt = 'Baby Ganesh — student Christmas card, 2026'
WHERE id = 'students-christmas-ganpati-1';

UPDATE products SET
  title = 'Lord Ganesha',
  description = 'A student drawing of Ganesha in blessing, from the 2026 Christmas cards.',
  image_alt = 'Lord Ganesha — student Christmas card, 2026'
WHERE id = 'students-christmas-ganpati-2';

UPDATE products SET
  title = 'Hanuman',
  description = 'A student drawing of Hanuman in meditation, lotus in hand, from the 2026 Christmas cards.',
  image_alt = 'Hanuman — student Christmas card, 2026'
WHERE id = 'students-christmas-hanuman';

UPDATE products SET
  title = 'Rainbow Lorikeet',
  description = 'A student study of a rainbow lorikeet on a leafy branch, from the 2026 Christmas cards.',
  image_alt = 'Rainbow Lorikeet — student Christmas card, 2026'
WHERE id = 'students-christmas-parrot-1';

UPDATE products SET
  title = 'Kingfisher',
  description = 'A student study of a kingfisher on a branch, from the 2026 Christmas cards.',
  image_alt = 'Kingfisher — student Christmas card, 2026'
WHERE id = 'students-christmas-parrot-2';

UPDATE products SET
  title = 'Green Parrot',
  description = 'A student study of a green parrot with a red tail, from the 2026 Christmas cards.',
  image_alt = 'Green Parrot — student Christmas card, 2026'
WHERE id = 'students-christmas-parrot-3';

UPDATE products SET
  title = 'Mountain Village',
  description = 'A student landscape of cottages, pines, and a mountain path, from the 2026 Christmas cards.',
  image_alt = 'Mountain Village — student Christmas card, 2026'
WHERE id = 'students-christmas-scenery-1';

UPDATE products SET
  title = 'Cottage Path',
  description = 'A student landscape of a red-roofed cottage, a bridge, and evening sun, from the 2026 Christmas cards.',
  image_alt = 'Cottage Path — student Christmas card, 2026'
WHERE id = 'students-christmas-scenery-2';

UPDATE products SET
  title = 'Reaching for the Moon',
  description = 'A student drawing of a girl in a red dress reaching toward the night sky, from the 2026 Christmas cards.',
  image_alt = 'Reaching for the Moon — student Christmas card, 2026'
WHERE id = 'students-christmas-scenery-3';

UPDATE products SET
  title = 'Storybook House',
  description = 'A student drawing of a turreted cottage with red roofs, from the 2026 Christmas cards.',
  image_alt = 'Storybook House — student Christmas card, 2026'
WHERE id = 'students-christmas-scenery-4';

UPDATE products SET
  title = 'Woman in Profile',
  description = 'A student portrait of a woman with lotuses, from the 2026 Christmas cards.',
  image_alt = 'Woman in Profile — student Christmas card, 2026'
WHERE id = 'students-christmas-woman';
