                                                                                                                                                                                                    -- Purchase redesign — run once in phpMyAdmin → SQL
                                                                                                                                                                                                    -- 1) Originals: editable dimensions + single price
                                                                                                                                                                                                    -- 2) product_sizes = print A1/A2/A3 prices (convert from old "original×50%" model)
                                                                                                                                                                                                    -- 3) Remove Children / students-christmas from purchase tags (images stay for Courses gallery)
                                                                                                                                                                                                    --
                                                                                                                                                                                                    -- If a column already exists, skip that ALTER line and continue.

                                                                                                                                                                                                    SET NAMES utf8mb4;

                                                                                                                                                                                                    -- Place after product_additional_details (already added by product-additional-details.sql)
                                                                                                                                                                                                    ALTER TABLE products
                                                                                                                                                                                                      ADD COLUMN original_dimensions VARCHAR(64) NOT NULL DEFAULT '10 inch × 13 inch' AFTER product_additional_details;

                                                                                                                                                                                                    ALTER TABLE products
                                                                                                                                                                                                      ADD COLUMN original_price DECIMAL(10,2) NULL AFTER original_dimensions;

                                                                                                                                                                                                    ALTER TABLE products
                                                                                                                                                                                                      ADD COLUMN original_compare_at DECIMAL(10,2) NULL AFTER original_price;

                                                                                                                                                                                                    UPDATE products p
                                                                                                                                                                                                    INNER JOIN product_sizes ps ON ps.product_id = p.id AND ps.size_code IN ('S', 'A1')
                                                                                                                                                                                                    SET
                                                                                                                                                                                                      p.original_price = COALESCE(p.original_price, ps.price),
                                                                                                                                                                                                      p.original_compare_at = COALESCE(p.original_compare_at, ps.compare_at);

                                                                                                                                                                                                    UPDATE products
                                                                                                                                                                                                    SET original_dimensions = '10 inch × 13 inch'
                                                                                                                                                                                                    WHERE original_dimensions IS NULL OR TRIM(original_dimensions) = '';

                                                                                                                                                                                                    -- Convert size-row prices to real print prices (previously original prices with 50% on the site)
                                                                                                                                                                                                    UPDATE product_sizes
                                                                                                                                                                                                    SET
                                                                                                                                                                                                      price = ROUND(price * 0.5, 2),
                                                                                                                                                                                                      compare_at = CASE
                                                                                                                                                                                                        WHEN compare_at IS NULL THEN NULL
                                                                                                                                                                                                        ELSE ROUND(compare_at * 0.5, 2)
                                                                                                                                                                                                      END
                                                                                                                                                                                                    WHERE price > 0;

                                                                                                                                                                                                    -- Drop Children collection tag from products (gallery moves to Courses Running)
                                                                                                                                                                                                    UPDATE products
                                                                                                                                                                                                    SET tags = JSON_REMOVE(
                                                                                                                                                                                                      tags,
                                                                                                                                                                                                      JSON_UNQUOTE(JSON_SEARCH(tags, 'one', 'students-christmas'))
                                                                                                                                                                                                    )
                                                                                                                                                                                                    WHERE JSON_SEARCH(tags, 'one', 'students-christmas') IS NOT NULL;

                                                                                                                                                                                                    UPDATE products
                                                                                                                                                                                                    SET tags = JSON_REMOVE(
                                                                                                                                                                                                      tags,
                                                                                                                                                                                                      JSON_UNQUOTE(JSON_SEARCH(tags, 'one', 'children'))
                                                                                                                                                                                                    )
                                                                                                                                                                                                    WHERE JSON_SEARCH(tags, 'one', 'children') IS NOT NULL;

                                                                                                                                                                                                    -- Remove student-card pieces from the purchase catalogue entirely
                                                                                                                                                                                                    -- (images remain on disk for the Courses masonry gallery)
                                                                                                                                                                                                    UPDATE products
                                                                                                                                                                                                    SET tags = JSON_ARRAY()
                                                                                                                                                                                                    WHERE id LIKE 'students-christmas-%'
                                                                                                                                                                                                      OR image LIKE 'images/artwork/students-christmas-cards-2026/%';
