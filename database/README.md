# Products database (Hostinger MySQL)

## Files
- `database/schema.sql` — create tables
- `database/seed.sql` — insert the 11 products
- `database/install.sql` — schema + seed in one file (recommended)
- `includes/db.php` — PDO connection + product loader
- `includes/db.local.php.example` — copy to `db.local.php` with your password

## phpMyAdmin steps
1. Open your database `u531995345_lakshmi` in phpMyAdmin.
2. Click the **SQL** tab.
3. Open `database/install.sql` from this project, copy all contents, paste into SQL, click **Go**.
4. Confirm tables `products` and `product_sizes` exist and have rows.

## PHP credentials
1. Copy `includes/db.local.php.example` → `includes/db.local.php` (already created locally).
2. Set `pass` to your Hostinger MySQL password.
3. On Hostinger hosting, `host` must be `localhost`.

## Behaviour
`products()` loads **only** from MySQL via credentials in `includes/db.local.php`.
- DB failure → “Error fetching products…”
- Empty tables → “No products available right now.”
- No fallback to `productsData.php`.
