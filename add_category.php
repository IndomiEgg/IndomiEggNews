<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['category'])) {
    $category = trim($_POST['category']);
    $slug = strtolower(preg_replace('/[^a-z0-9]+/','-', $category));
    // Cek apakah kategori sudah ada
    $cek = $conn->prepare("SELECT 1 FROM categories WHERE name = ? OR slug = ? LIMIT 1");
    $cek->bind_param('ss', $category, $slug);
    $cek->execute();
    $cek->store_result();
    if ($cek->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        $stmt->bind_param('ss', $category, $slug);
        $stmt->execute();
    }
    $cek->close();
}
header('Location: admin_dashboard.php');
exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori - IndomiEggNews</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vast+Shadow&display=swap" rel="stylesheet">
    <script src="js/admin.js" defer></script>
</head>
<body>
    <!-- Body content here -->
</body>
</html>
