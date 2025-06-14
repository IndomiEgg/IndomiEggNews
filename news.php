<?php
// news.php - display single news by slug
include 'db.php';
if (!isset($_GET['slug'])) {
    die('No news selected.');
}
$slug = $conn->real_escape_string($_GET['slug']);
$res = $conn->query("SELECT posts.*, categories.name as category_name FROM posts LEFT JOIN categories ON posts.category_id = categories.id WHERE posts.slug='$slug'");
if ($res->num_rows == 0) {
    die('News not found.');
}
$row = $res->fetch_assoc();
?><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IndomiEggNews</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vast+Shadow&display=swap" rel="stylesheet">
    <script src="js/admin.js" defer></script>
</head>
<body>
    <button class="sidebar-toggle-global" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    <div class="header">
        <div class="logo">IndomiEggNews</div>
        <form class="search-bar" method="get" action="index.php">
            <input type="text" name="q" placeholder="Cari berita...">
            <button type="submit">Cari</button>
        </form>
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="admin_dashboard.php">Admin Page</a>
        </div>
    </div>
    <div class="container" style="align-items:flex-start;">
        <div class="sidebar" id="sidebarMenu">
            <h3>Kategori Berita</h3>
            <ul class="sidebar-category-list">
            <?php
            $catres = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
            $hasFinance = false;
            while($cat = $catres->fetch_assoc()) {
                $cat_id = $cat['id'];
                $cat_name = $cat['name'];
                $isFinance = (strtolower($cat_name) === 'finance');
                $hasFinance = $hasFinance || $isFinance;
                $catColors = [
                    0 => '#ffe082', 1 => '#b3e5fc', 2 => '#c8e6c9', 3 => '#ffd180', 4 => '#f8bbd0',
                    5 => '#d1c4e9', 6 => '#fff9c4', 7 => '#b2dfdb', 8 => '#ffccbc', 9 => '#e1bee7',
                ];
                $colorIdx = $cat_id % count($catColors);
                $catBoxStyle = "background:{$catColors[$colorIdx]};color:#222;border-radius:16px;padding:0.18em 1.1em;font-weight:600;display:inline-block;margin-bottom:0.2em;min-width:80px;text-align:center;border:1.5px solid #eee;";
                echo "<li class='sidebar-cat-item'>";
                echo "<a href='javascript:void(0)' class='sidebar-cat-link' data-cat='$cat_id' style='$catBoxStyle'>".htmlspecialchars($cat_name)."</a>";
                echo "<div class='sidebar-news-list' id='cat-news-$cat_id' style='display:none;'>";
                $newsres = $conn->query("SELECT slug, title FROM posts WHERE category_id = $cat_id ORDER BY id DESC LIMIT 20");
                if ($newsres->num_rows > 0) {
                    while($news = $newsres->fetch_assoc()) {
                        echo "<a class='news-title-link sidebar-news-title-ellipsis' href='news.php?slug=".urlencode($news['slug'])."' title='".htmlspecialchars($news['title'])."'>".mb_strimwidth(htmlspecialchars($news['title']),0,40,'...')."</a>";
                    }
                } else if ($isFinance) {
                    echo "<span class='sidebar-news-title-ellipsis' style='color:#aaa;'>Belum ada berita Finance</span>";
                }
                echo "</div>";
                echo "</li>";
            }
            if (!$hasFinance) {
                echo "<li class='sidebar-cat-item'>";
                echo "<span class='sidebar-cat-link log-cat-box' style='cursor:default;'>Finance</span>";
                echo "<div class='sidebar-news-list' style='display:block;'><span class='sidebar-news-title-ellipsis' style='color:#aaa;'>Belum ada berita Finance</span></div>";
                echo "</li>";
            }
            ?>
            </ul>
        </div>
        <div class="sidebar-backdrop" onclick="closeSidebar()"></div>
        <div class="news-content">
            <h1><?php echo htmlspecialchars($row['title']); ?></h1>
            <div>Kategori: <?php echo !empty($row['category_name']) ? htmlspecialchars($row['category_name']) : '<span style="color:#aaa">(Tidak ada kategori)</span>'; ?></div>
            <?php if (!empty($row['image'])): ?>
                <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="Gambar Berita" class="news-detail-img">
            <?php endif; ?>
            <div><?php echo $row['content']; ?></div>
            <?php if (!empty($row['created_at'])): ?>
                <div><small>Posted on: <?php echo $row['created_at']; ?></small></div>
            <?php endif; ?>
        </div>
        <div class="top-news-sidebar">
            <div class="top-news-card">
                <h3 class="top-news-title">Top News</h3>
                <div class="top-news-list">
                <?php
                $topres = $conn->query("SELECT posts.slug, posts.title, posts.image, posts.content, categories.name as category_name FROM posts LEFT JOIN categories ON posts.category_id = categories.id ORDER BY RAND() LIMIT 7");
                if ($topres && $topres->num_rows > 0) {
                    while($top = $topres->fetch_assoc()) {
                        echo '<div class="news-item">';
                        if (!empty($top['image'])) {
                            echo '<img class="news-thumb" src="images/'.htmlspecialchars($top['image']).'" alt="thumb">';
                        } else {
                            echo '<div class="news-thumb"></div>';
                        }
                        echo '<div class="news-info">';
                        echo '<div class="news-title"><a href="news.php?slug='.urlencode($top['slug']).'">'.htmlspecialchars($top['title']).'</a></div>';
                        echo '<div class="news-meta">Kategori: '.htmlspecialchars($top['category_name']).'</div>';
                        echo '<div>'.mb_strimwidth(strip_tags($top['content']),0,70,"...").'</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div style="color:#aaa;font-style:italic;">Belum ada berita.</div>';
                }
                ?>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="footer-logo">IndomiEggNews</div>
        <div>Kontak: info@indomieggnews.com | Telp: 0812-3456-7890</div>
        <div class="footer-copyright">&copy; <?php echo date('Y'); ?> IndomiEggNews</div>
    </div>
</body>
</html>
