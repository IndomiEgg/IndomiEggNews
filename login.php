<?php
session_start();
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    // Ganti 'admin123' dengan password admin yang Anda inginkan
    if ($password === 'admin123') {
        $_SESSION['is_admin'] = true;
        header('Location: admin_dashboard.php');
        exit;
    } else {
        $login_error = 'Password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IndomiEggNews</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vast+Shadow&display=swap" rel="stylesheet">
    <script src="js/admin.js" defer></script>
</head>
<body>
<div class="login-box">
    <h2>Login Admin</h2>
    <?php if ($login_error) echo '<div class="error">'.$login_error.'</div>'; ?>
    <form method="post" action="login.php" class="login-form">
        <input type="password" name="password" placeholder="Password admin" required>
        <button type="submit">Login</button>
        <a href="index.php" class="back-home-btn">&larr; Kembali ke Home</a>
    </form>
</div>
</body>
</html>
