<?php include 'db.php'; ?>
<html lang="en">
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
            <input type="text" name="q" placeholder="Cari berita..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            <?php if (!empty($_GET['category'])) echo '<input type="hidden" name="category" value="'.htmlspecialchars($_GET['category']).'">'; ?>
            <button type="submit">Cari</button>
        </form>
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="admin_dashboard.php">Admin Page</a>
        </div>
    </div>
    <div class="container">
        <!-- Fun Fact Card di kiri -->
        <div class="left-funfact-col">
            <?php
            $funfacts = [
                "Tahukah kamu? Ayam betina bisa bertelur tanpa ayam jantan, tapi telurnya tidak akan menetas!",
                "Fakta: Indonesia adalah produsen telur terbesar di Asia Tenggara.",
                "Fun Fact: Telur mengandung hampir semua vitamin kecuali vitamin C.",
                "Tahukah kamu? Warna cangkang telur (putih/coklat) tergantung ras ayam, bukan gizinya!",
                "Fakta: Konsumsi telur per kapita di Indonesia terus meningkat setiap tahun.",
                "Fun Fact: Telur bisa bertahan segar di suhu ruang hingga 2 minggu jika tidak dicuci.",
                "Tahukah kamu? Telur rebus lebih tahan lama daripada telur mentah di suhu ruang.",
                "Fakta: Telur adalah sumber protein hewani termurah dan mudah didapat.",
                "Fun Fact: Ayam bisa bertelur hampir setiap hari, rata-rata 250-300 telur/tahun.",
                "Tahukah kamu? Telur mengandung kolin yang baik untuk otak dan memori.",
                "Fakta: Telur ayam kampung biasanya lebih kecil dari telur ayam ras.",
                "Fun Fact: Telur mengandung lutein yang baik untuk kesehatan mata.",
                "Tahukah kamu? Telur mentah mengandung lebih banyak air daripada telur rebus.",
                "Fakta: Telur bebek memiliki rasa yang lebih kuat dibanding telur ayam.",
                "Fun Fact: Telur asin dibuat dengan merendam telur bebek dalam larutan garam.",
                "Tahukah kamu? Telur puyuh mengandung lebih banyak protein per gram dibanding telur ayam.",
                "Fakta: Telur dapat digunakan sebagai bahan pengental dalam masakan.",
                "Fun Fact: Telur mengandung vitamin D alami yang baik untuk tulang.",
                "Tahukah kamu? Telur bisa digunakan sebagai masker rambut alami.",
                "Fakta: Telur yang segar akan tenggelam jika dimasukkan ke air.",
                "Fun Fact: Telur dapat digunakan sebagai bahan perekat alami.",
                "Tahukah kamu? Telur orak-arik adalah menu sarapan populer di seluruh dunia.",
                "Fakta: Telur mengandung asam amino esensial yang dibutuhkan tubuh.",
                "Fun Fact: Telur dapat diolah menjadi berbagai makanan seperti omelet, dadar, dan kue.",
                "Tahukah kamu? Telur yang sudah retak sebaiknya segera dimasak.",
                "Fakta: Telur dapat membantu mengikat bahan-bahan dalam adonan kue.",
                "Fun Fact: Telur mengandung selenium yang baik untuk sistem imun.",
                "Tahukah kamu? Telur bisa digunakan untuk membuat mayones.",
                "Fakta: Telur yang disimpan di kulkas bisa bertahan hingga 1 bulan.",
                "Fun Fact: Telur rebus yang sudah dikupas sebaiknya disimpan dalam air agar tetap segar.",
                "Tahukah kamu? Telur mengandung lemak sehat yang dibutuhkan tubuh.",
                "Fakta: Telur dapat membantu pertumbuhan otot karena kandungan proteinnya.",
                "Fun Fact: Telur bisa digunakan sebagai bahan pelapis gorengan agar renyah.",
                "Tahukah kamu? Telur bebek sering digunakan untuk membuat kue tradisional.",
                "Fakta: Telur yang sudah busuk akan mengapung di air.",
                "Fun Fact: Telur dapat digunakan untuk membuat puding dan custard.",
                "Tahukah kamu? Telur puyuh sering dijadikan topping pada soto dan bakso.",
                "Fakta: Telur mengandung kolesterol, tapi tidak selalu meningkatkan kolesterol darah.",
                "Fun Fact: Telur bisa digunakan untuk membuat adonan pancake lebih lembut.",
                "Tahukah kamu? Telur ayam negeri lebih banyak dikonsumsi di Indonesia.",
                "Fakta: Telur dapat membantu menjaga kesehatan kulit.",
                "Fun Fact: Telur bisa digunakan sebagai bahan pengental sup.",
                "Tahukah kamu? Telur yang sudah matang lebih mudah dicerna tubuh.",
                "Fakta: Telur mengandung vitamin B12 yang penting untuk saraf.",
                "Fun Fact: Telur dapat digunakan untuk membuat saus hollandaise.",
                "Tahukah kamu? Telur mentah sebaiknya tidak dikonsumsi langsung karena risiko bakteri.",
                "Fakta: Telur dapat digunakan sebagai bahan dasar es krim.",
                "Fun Fact: Telur mengandung zat besi yang penting untuk darah.",
                "Tahukah kamu? Telur bisa digunakan untuk membuat roti lebih mengembang.",
                "Fakta: Telur yang berwarna coklat atau putih sama gizinya, hanya berbeda jenis ayamnya.",
                "Fun Fact: Telur bisa digunakan untuk membuat adonan donat lebih empuk."
            ];
            $randomFact = $funfacts[array_rand($funfacts)];
            ?>
            <div class="funfact-card main-funfact-card">
                <div class="funfact-title"><i class="fas fa-lightbulb"></i> Fun Fact</div>
                <div class="funfact-content"><?php echo htmlspecialchars($randomFact); ?></div>
            </div>
        </div>
        <div class="sidebar" id="sidebarMenu">
            <h3>Kategori Berita</h3>
            <ul class="sidebar-category-list">
            <?php
            $catres = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
            $hasFinance = false;
            $catColors = [
                '#ffe082','#b3e5fc','#c8e6c9','#ffd180','#f8bbd0','#d1c4e9','#fff9c4','#b2dfdb','#ffccbc','#e1bee7'
            ];
            while($cat = $catres->fetch_assoc()) {
                $cat_id = $cat['id'];
                $cat_name = $cat['name'];
                $isFinance = (strtolower($cat_name) === 'finance');
                $hasFinance = $hasFinance || $isFinance;
                $colorIdx = $cat_id % count($catColors);
            ?>
                <li class="sidebar-cat-item">
                    <a href="javascript:void(0)" class="sidebar-cat-link" data-cat="<?php echo $cat_id; ?>" style="background:<?php echo $catColors[$colorIdx]; ?>;color:#222;border-radius:16px;padding:0.18em 1.1em;font-weight:600;display:inline-block;margin-bottom:0.2em;min-width:80px;text-align:center;border:1.5px solid #eee;">
                        <?php echo htmlspecialchars($cat_name); ?>
                    </a>
                    <div class="sidebar-news-list" id="cat-news-<?php echo $cat_id; ?>">
                        <?php
                        $newsres = $conn->query("SELECT slug, title FROM posts WHERE category_id = $cat_id ORDER BY id DESC LIMIT 20");
                        if ($newsres->num_rows > 0) {
                            while($news = $newsres->fetch_assoc()) {
                        ?>
                            <a class="news-title-link sidebar-news-title-ellipsis" href="news.php?slug=<?php echo urlencode($news['slug']); ?>" title="<?php echo htmlspecialchars($news['title']); ?>">
                                <?php echo mb_strimwidth(htmlspecialchars($news['title']),0,40,'...'); ?>
                            </a>
                        <?php }} else if ($isFinance) { ?>
                            <span class="sidebar-news-title-ellipsis" style="color:#aaa;">Belum ada berita Finance</span>
                        <?php } ?>
                    </div>
                </li>
            <?php }
            if (!$hasFinance) { ?>
                <li class="sidebar-cat-item">
                    <span class="sidebar-cat-link log-cat-box" style="cursor:default;">Finance</span>
                    <div class="sidebar-news-list"><span class="sidebar-news-title-ellipsis" style="color:#aaa;">Belum ada berita Finance</span></div>
                </li>
            <?php } ?>
            </ul>
        </div>
        <div class="sidebar-backdrop"></div>
        <div class="content">
            <?php
            // --- CAROUSEL BERITA ACAK ---
            $carouselres = $conn->query("SELECT posts.slug, posts.title, posts.image, categories.name as category_name FROM posts LEFT JOIN categories ON posts.category_id = categories.id ORDER BY RAND() LIMIT 5");
            if ($carouselres && $carouselres->num_rows > 0) {
            ?>
            <div class="news-carousel-container">
                <div class="news-carousel" id="newsCarousel">
                    <?php while($c = $carouselres->fetch_assoc()) { ?>
                    <div class="carousel-item">
                        <?php if (!empty($c['image'])) { ?>
                            <img class="carousel-img" src="images/<?php echo htmlspecialchars($c['image']); ?>" alt="thumb">
                        <?php } else { ?>
                            <div class="carousel-img carousel-img-empty"></div>
                        <?php } ?>
                        <div class="carousel-caption">
                            <div class="carousel-title"><a href="news.php?slug=<?php echo urlencode($c['slug']); ?>"><?php echo htmlspecialchars($c['title']); ?></a></div>
                            <div class="carousel-meta">Kategori: <?php echo htmlspecialchars($c['category_name']); ?></div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <button class="carousel-nav prev" id="carouselPrev">&#10094;</button>
                <button class="carousel-nav next" id="carouselNext">&#10095;</button>
            </div>
            <?php } ?>
            <?php
            // --- END CAROUSEL ---
            $per_page = 5;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $per_page;
            $where = [];
            if (!empty($_GET['category'])) {
                $cat = $conn->real_escape_string($_GET['category']);
                $catres = $conn->query("SELECT id FROM categories WHERE name = '".$cat."' LIMIT 1");
                $catrow = $catres->fetch_assoc();
                $where[] = $catrow ? "category_id = ".$catrow['id'] : "0";
            }
            if (!empty($_GET['q'])) {
                $q = $conn->real_escape_string($_GET['q']);
                $where[] = "(title LIKE '%$q%' OR content LIKE '%$q%')";
            }
            $whereSql = $where ? 'WHERE '.implode(' AND ', $where) : '';
            $totalres = $conn->query("SELECT COUNT(*) as total FROM posts $whereSql");
            $total = $totalres->fetch_assoc()['total'];
            $pages = ceil($total / $per_page);
            $result = $conn->query("SELECT posts.*, categories.name as category_name FROM posts LEFT JOIN categories ON posts.category_id = categories.id $whereSql ORDER BY posts.id DESC LIMIT $per_page OFFSET $offset");
            if ($total == 0) {
                if (!empty($_GET['q'])) {
                    echo '<div class="no-news">Tidak ada berita ditemukan untuk kata kunci: <b>'.htmlspecialchars($_GET['q']).'</b></div>';
                } else {
                    echo '<div class="no-news">Tidak ada berita ditemukan.</div>';
                }
            }
            while($row = $result->fetch_assoc()) {
            ?>
            <div class="news-item">
                <?php if (!empty($row['image'])) { ?>
                    <img class="news-thumb" src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="thumb">
                <?php } else { ?>
                    <div class="news-thumb"></div>
                <?php } ?>
                <div class="news-info">
                    <div class="news-title"><a href="news.php?slug=<?php echo urlencode($row['slug']); ?>"><?php echo htmlspecialchars($row['title']); ?></a></div>
                    <div class="news-meta">Kategori: <?php echo htmlspecialchars($row['category_name']); ?></div>
                    <div><?php echo mb_strimwidth(strip_tags($row['content']),0,120,"..."); ?></div>
                </div>
            </div>
            <?php } ?>
            <div class="pagination">
                <?php for($i=1;$i<=$pages;$i++):
                    $active = ($i == $page) ? 'class="active"' : '';
                    $url = '?page='.$i;
                    if (!empty($_GET['category'])) $url .= '&category='.urlencode($_GET['category']);
                    if (!empty($_GET['q'])) $url .= '&q='.urlencode($_GET['q']);
                ?>
                    <a href="<?php echo $url; ?>" <?php echo $active; ?>><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>
        <div class="top-news-sidebar">
            <div class="top-news-card">
                <h3 class="top-news-title">Top News</h3>
                <div class="top-news-list">
                <?php
                $topres = $conn->query("SELECT posts.slug, posts.title, posts.image, posts.content, categories.name as category_name FROM posts LEFT JOIN categories ON posts.category_id = categories.id ORDER BY RAND() LIMIT 7");
                if ($topres && $topres->num_rows > 0) {
                    while($top = $topres->fetch_assoc()) {
                ?>
                    <div class="news-item">
                        <?php if (!empty($top['image'])) { ?>
                            <img class="news-thumb" src="images/<?php echo htmlspecialchars($top['image']); ?>" alt="thumb">
                        <?php } else { ?>
                            <div class="news-thumb"></div>
                        <?php } ?>
                        <div class="news-info">
                            <div class="news-title"><a href="news.php?slug=<?php echo urlencode($top['slug']); ?>"><?php echo htmlspecialchars($top['title']); ?></a></div>
                            <div class="news-meta">Kategori: <?php echo htmlspecialchars($top['category_name']); ?></div>
                            <div><?php echo mb_strimwidth(strip_tags($top['content']),0,70,"..."); ?></div>
                        </div>
                    </div>
                <?php }} else { ?>
                    <div class="no-news" style="color:#aaa;font-style:italic;">Belum ada berita.</div>
                <?php } ?>
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