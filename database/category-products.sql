-- Collection products from artwork folders.
-- Run once in phpMyAdmin. INSERT IGNORE keeps existing rows.
SET NAMES utf8mb4;

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('animals-dog-1', 'The Pug', 'A colour-pencil portrait of a pug, tongue out, drawn with the softness of fur and the shine of a wet nose.', 'images/artwork/animals/dog-1.jpeg', 'The Pug — artwork by Lakshmi Sridhar', '["original", "print", "animals"]', 'available', 0, 'S', 100);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('animals-dog-1', 'S', 'A1', 89.00, 129.00),
('animals-dog-1', 'M', 'A2', 119.00, 159.00),
('animals-dog-1', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('animals-dog-2', 'German Shepherd', 'A German shepherd holding a braided rope toy — alert, proud, and ready to play.', 'images/artwork/animals/dog-2.jpeg', 'German Shepherd — artwork by Lakshmi Sridhar', '["original", "print", "animals"]', 'available', 0, 'S', 101);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('animals-dog-2', 'S', 'A1', 89.00, 129.00),
('animals-dog-2', 'M', 'A2', 119.00, 159.00),
('animals-dog-2', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('animals-elephant', 'Mother and Calf', 'A graphite study of an elephant and her calf, drawn quietly on a dark ground.', 'images/artwork/animals/elephant.jpeg', 'Mother and Calf — artwork by Lakshmi Sridhar', '["original", "print", "animals"]', 'available', 0, 'S', 102);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('animals-elephant', 'S', 'A1', 89.00, 129.00),
('animals-elephant', 'M', 'A2', 119.00, 159.00),
('animals-elephant', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('animals-horse', 'Number 2477', 'A thoroughbred in bridle and race number, drawn in colour pencil against a quiet white field.', 'images/artwork/animals/horse.jpeg', 'Number 2477 — artwork by Lakshmi Sridhar', '["original", "print", "animals"]', 'available', 0, 'S', 103);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('animals-horse', 'S', 'A1', 89.00, 129.00),
('animals-horse', 'M', 'A2', 119.00, 159.00),
('animals-horse', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('animals-lion', 'The Lion', 'A lion in profile, mane drawn hair by hair in warm golds and umbers.', 'images/artwork/animals/lion.jpeg', 'The Lion — artwork by Lakshmi Sridhar', '["original", "print", "animals"]', 'available', 0, 'S', 104);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('animals-lion', 'S', 'A1', 89.00, 129.00),
('animals-lion', 'M', 'A2', 119.00, 159.00),
('animals-lion', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('animals-parrot-1', 'Blue-and-Gold Macaw', 'A macaw study in cobalt, gold, and green, looking back over the shoulder.', 'images/artwork/animals/parrot-1.jpeg', 'Blue-and-Gold Macaw — artwork by Lakshmi Sridhar', '["original", "print", "animals"]', 'available', 0, 'S', 105);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('animals-parrot-1', 'S', 'A1', 89.00, 129.00),
('animals-parrot-1', 'M', 'A2', 119.00, 159.00),
('animals-parrot-1', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('animals-parrot-2', 'Scarlet Macaw', 'A scarlet macaw on a perch — red, green, and blue layered slowly in colour pencil.', 'images/artwork/animals/parrot-2.jpeg', 'Scarlet Macaw — artwork by Lakshmi Sridhar', '["original", "print", "animals"]', 'available', 0, 'S', 106);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('animals-parrot-2', 'S', 'A1', 89.00, 129.00),
('animals-parrot-2', 'M', 'A2', 119.00, 159.00),
('animals-parrot-2', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('animals-tiger', 'The Leopard', 'A leopard in profile, spots and whiskers drawn in graphite on a warm ground.', 'images/artwork/animals/tiger.jpeg', 'The Leopard — artwork by Lakshmi Sridhar', '["original", "print", "animals"]', 'available', 0, 'S', 107);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('animals-tiger', 'S', 'A1', 89.00, 129.00),
('animals-tiger', 'M', 'A2', 119.00, 159.00),
('animals-tiger', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('animals-tigers-family', 'Lion Family', 'A lion, lioness, and cub asleep together, drawn in soft colour pencil.', 'images/artwork/animals/tigers-family.jpeg', 'Lion Family — artwork by Lakshmi Sridhar', '["original", "print", "animals"]', 'available', 0, 'S', 108);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('animals-tigers-family', 'S', 'A1', 89.00, 129.00),
('animals-tigers-family', 'M', 'A2', 119.00, 159.00),
('animals-tigers-family', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('still-life-apples', 'Red Apples', 'A close still life of red apples — water on the skin, leaves still attached.', 'images/artwork/still-life/apples.jpeg', 'Red Apples — artwork by Lakshmi Sridhar', '["original", "print", "still-life"]', 'available', 0, 'S', 109);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('still-life-apples', 'S', 'A1', 89.00, 129.00),
('still-life-apples', 'M', 'A2', 119.00, 159.00),
('still-life-apples', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('still-life-bear', 'On the Rocks', 'Whisky poured over ice — glass, liquid, and light drawn in colour pencil.', 'images/artwork/still-life/bear.jpeg', 'On the Rocks — artwork by Lakshmi Sridhar', '["original", "print", "still-life"]', 'available', 0, 'S', 110);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('still-life-bear', 'S', 'A1', 89.00, 129.00),
('still-life-bear', 'M', 'A2', 119.00, 159.00),
('still-life-bear', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('still-life-grapes', 'Autumn Grapes', 'Dark grapes in a silver bowl, with maple leaves turning red.', 'images/artwork/still-life/grapes.jpeg', 'Autumn Grapes — artwork by Lakshmi Sridhar', '["original", "print", "still-life"]', 'available', 0, 'S', 111);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('still-life-grapes', 'S', 'A1', 89.00, 129.00),
('still-life-grapes', 'M', 'A2', 119.00, 159.00),
('still-life-grapes', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('still-life-lemon', 'Bowl of Limes', 'Limes in a blue-and-white bowl, one cut open beside the knife.', 'images/artwork/still-life/lemon.jpeg', 'Bowl of Limes — artwork by Lakshmi Sridhar', '["original", "print", "still-life"]', 'available', 0, 'S', 112);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('still-life-lemon', 'S', 'A1', 89.00, 129.00),
('still-life-lemon', 'M', 'A2', 119.00, 159.00),
('still-life-lemon', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('portraits-actress', 'The Actress', 'A graphite portrait of a young woman — necklace, dark hair, and a quiet smile.', 'images/artwork/portraits/actress.jpeg', 'The Actress — artwork by Lakshmi Sridhar', '["original", "print", "portraits"]', 'available', 0, 'S', 113);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('portraits-actress', 'S', 'A1', 89.00, 129.00),
('portraits-actress', 'M', 'A2', 119.00, 159.00),
('portraits-actress', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('portraits-old-man-1', 'Crown of Thorns', 'A graphite study of Christ, eyes closed, wearing the crown of thorns.', 'images/artwork/portraits/old-man-1.jpeg', 'Crown of Thorns — artwork by Lakshmi Sridhar', '["original", "print", "portraits"]', 'available', 0, 'S', 114);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('portraits-old-man-1', 'S', 'A1', 89.00, 129.00),
('portraits-old-man-1', 'M', 'A2', 119.00, 159.00),
('portraits-old-man-1', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('portraits-old-man-2', 'The Elder', 'A graphite portrait of an older man in a head wrap, every line of the face held.', 'images/artwork/portraits/old-man-2.jpeg', 'The Elder — artwork by Lakshmi Sridhar', '["original", "print", "portraits"]', 'available', 0, 'S', 115);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('portraits-old-man-2', 'S', 'A1', 89.00, 129.00),
('portraits-old-man-2', 'M', 'A2', 119.00, 159.00),
('portraits-old-man-2', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('portraits-south-filmstar', 'The Film Star', 'A graphite portrait of a South Indian film star, looking back with a smile.', 'images/artwork/portraits/south-filmstar.jpeg', 'The Film Star — artwork by Lakshmi Sridhar', '["original", "print", "portraits"]', 'available', 0, 'S', 116);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('portraits-south-filmstar', 'S', 'A1', 89.00, 129.00),
('portraits-south-filmstar', 'M', 'A2', 119.00, 159.00),
('portraits-south-filmstar', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('portraits-tribal-man', 'The Warrior', 'A colour-pencil portrait of a man in red face paint and a single braid.', 'images/artwork/portraits/tribal-man.jpeg', 'The Warrior — artwork by Lakshmi Sridhar', '["original", "print", "portraits"]', 'available', 0, 'S', 117);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('portraits-tribal-man', 'S', 'A1', 89.00, 129.00),
('portraits-tribal-man', 'M', 'A2', 119.00, 159.00),
('portraits-tribal-man', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('portraits-woman-dance', 'The Dancer', 'A Bharatanatyam dancer in jewellery and mudra, drawn in graphite.', 'images/artwork/portraits/woman-dance.jpeg', 'The Dancer — artwork by Lakshmi Sridhar', '["original", "print", "portraits"]', 'available', 0, 'S', 118);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('portraits-woman-dance', 'S', 'A1', 89.00, 129.00),
('portraits-woman-dance', 'M', 'A2', 119.00, 159.00),
('portraits-woman-dance', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('portraits-women', 'Two Women', 'Two women in traditional jewellery and jasmine — one in profile, one seen from behind.', 'images/artwork/portraits/women.jpeg', 'Two Women — artwork by Lakshmi Sridhar', '["original", "print", "portraits"]', 'available', 0, 'S', 119);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('portraits-women', 'S', 'A1', 89.00, 129.00),
('portraits-women', 'M', 'A2', 119.00, 159.00),
('portraits-women', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-apple', 'The Apple', 'A student close-up of a red apple with dew, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/apple.jpeg', 'The Apple — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 120);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-apple', 'S', 'A1', 89.00, 129.00),
('students-christmas-apple', 'M', 'A2', 119.00, 159.00),
('students-christmas-apple', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-butterfly', 'Monarch', 'A student study of a butterfly on orange blossom, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/butterfly.jpeg', 'Monarch — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 121);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-butterfly', 'S', 'A1', 89.00, 129.00),
('students-christmas-butterfly', 'M', 'A2', 119.00, 159.00),
('students-christmas-butterfly', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-cindrella', 'Snow White', 'A student drawing of Snow White in the woods with birds and a fawn, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/cindrella.jpeg', 'Snow White — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 122);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-cindrella', 'S', 'A1', 89.00, 129.00),
('students-christmas-cindrella', 'M', 'A2', 119.00, 159.00),
('students-christmas-cindrella', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-eating', 'Cherry', 'A student study of a cherry held between the lips, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/eating.jpeg', 'Cherry — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 123);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-eating', 'S', 'A1', 89.00, 129.00),
('students-christmas-eating', 'M', 'A2', 119.00, 159.00),
('students-christmas-eating', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-fruits', 'Fruit and Vase', 'A student still life of fruit, grapes, and a blue vase, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/fruits.jpeg', 'Fruit and Vase — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 124);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-fruits', 'S', 'A1', 89.00, 129.00),
('students-christmas-fruits', 'M', 'A2', 119.00, 159.00),
('students-christmas-fruits', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-ganpati-1', 'Baby Ganesh', 'A student drawing of baby Ganesh on a swing, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/ganpati-1.jpeg', 'Baby Ganesh — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 125);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-ganpati-1', 'S', 'A1', 89.00, 129.00),
('students-christmas-ganpati-1', 'M', 'A2', 119.00, 159.00),
('students-christmas-ganpati-1', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-ganpati-2', 'Lord Ganesha', 'A student drawing of Ganesha in blessing, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/ganpati-2.jpeg', 'Lord Ganesha — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 126);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-ganpati-2', 'S', 'A1', 89.00, 129.00),
('students-christmas-ganpati-2', 'M', 'A2', 119.00, 159.00),
('students-christmas-ganpati-2', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-hanuman', 'Hanuman', 'A student drawing of Hanuman in meditation, lotus in hand, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/hanuman.jpeg', 'Hanuman — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 127);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-hanuman', 'S', 'A1', 89.00, 129.00),
('students-christmas-hanuman', 'M', 'A2', 119.00, 159.00),
('students-christmas-hanuman', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-parrot-1', 'Rainbow Lorikeet', 'A student study of a rainbow lorikeet on a leafy branch, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/parrot-1.jpeg', 'Rainbow Lorikeet — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 128);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-parrot-1', 'S', 'A1', 89.00, 129.00),
('students-christmas-parrot-1', 'M', 'A2', 119.00, 159.00),
('students-christmas-parrot-1', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-parrot-2', 'Kingfisher', 'A student study of a kingfisher on a branch, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/parrot-2.jpeg', 'Kingfisher — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 129);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-parrot-2', 'S', 'A1', 89.00, 129.00),
('students-christmas-parrot-2', 'M', 'A2', 119.00, 159.00),
('students-christmas-parrot-2', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-parrot-3', 'Green Parrot', 'A student study of a green parrot with a red tail, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/parrot-3.jpeg', 'Green Parrot — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 130);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-parrot-3', 'S', 'A1', 89.00, 129.00),
('students-christmas-parrot-3', 'M', 'A2', 119.00, 159.00),
('students-christmas-parrot-3', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-scenery-1', 'Mountain Village', 'A student landscape of cottages, pines, and a mountain path, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/scenery-1.jpeg', 'Mountain Village — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 131);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-scenery-1', 'S', 'A1', 89.00, 129.00),
('students-christmas-scenery-1', 'M', 'A2', 119.00, 159.00),
('students-christmas-scenery-1', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-scenery-2', 'Cottage Path', 'A student landscape of a red-roofed cottage, a bridge, and evening sun, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/scenery-2.jpeg', 'Cottage Path — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 132);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-scenery-2', 'S', 'A1', 89.00, 129.00),
('students-christmas-scenery-2', 'M', 'A2', 119.00, 159.00),
('students-christmas-scenery-2', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-scenery-3', 'Reaching for the Moon', 'A student drawing of a girl in a red dress reaching toward the night sky, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/scenery-3.jpeg', 'Reaching for the Moon — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 133);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-scenery-3', 'S', 'A1', 89.00, 129.00),
('students-christmas-scenery-3', 'M', 'A2', 119.00, 159.00),
('students-christmas-scenery-3', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-scenery-4', 'Storybook House', 'A student drawing of a turreted cottage with red roofs, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/scenery-4.jpeg', 'Storybook House — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 134);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-scenery-4', 'S', 'A1', 89.00, 129.00),
('students-christmas-scenery-4', 'M', 'A2', 119.00, 159.00),
('students-christmas-scenery-4', 'L', 'A3', 149.00, 199.00);

INSERT IGNORE INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order) VALUES
('students-christmas-woman', 'Woman in Profile', 'A student portrait of a woman with lotuses, from the 2026 Christmas cards.', 'images/artwork/students-christmas-cards-2026/woman.jpeg', 'Woman in Profile — student Christmas card, 2026', '["original", "print", "students-christmas"]', 'available', 0, 'S', 135);
INSERT IGNORE INTO product_sizes (product_id, size_code, label, price, compare_at) VALUES
('students-christmas-woman', 'S', 'A1', 89.00, 129.00),
('students-christmas-woman', 'M', 'A2', 119.00, 159.00),
('students-christmas-woman', 'L', 'A3', 149.00, 199.00);

