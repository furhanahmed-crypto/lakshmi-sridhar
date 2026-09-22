<?php
require_once __DIR__ . '/auth.php';

$error = '';

if (admin_logged_in()) {
    header('Location: ' . admin_url('products.php'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = (string) ($_POST['password'] ?? '');
    if (admin_attempt_login($password)) {
        header('Location: ' . admin_url('products.php'));
        exit;
    }
    $error = 'Incorrect password.';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — <?= e(SITE_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css?v=<?= ASSET_VERSION ?>">
</head>

<body class="admin">
    <div class="login-wrap">
        <form class="login-card" method="post" autocomplete="current-password">
            <h1>Admin</h1>
            <p>Enter the password to manage products.</p>
            <?php if ($error): ?>
                <p class="flash flash--error"><?= e($error) ?></p>
            <?php endif; ?>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autofocus>
            </div>
            <button class="btn btn--primary" type="submit" style="width:100%;">Continue</button>
        </form>
    </div>
</body>

</html>