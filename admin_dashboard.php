<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}
include 'db.php';

// Proses tambah berita jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_news'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = null;
    if (!empty($_POST['category'])) {
        $category_id = intval($_POST['category']);
    }
    // Slug otomatis
    function slugify($text) {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        $text = trim($text, '-');
        return $text;
    }
    $slug = slugify($title);
    $baseSlug = $slug;
    $counter = 1;
    while ($conn->query("SELECT id FROM posts WHERE slug='$slug'")->num_rows > 0) {
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }
    // Upload gambar
    $image = '';
    $imagesDir = __DIR__ . '/images';
    if (!is_dir($imagesDir)) {
        mkdir($imagesDir, 0777, true);
    }
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = 'img_' . uniqid() . '.' . $ext;
        $target = $imagesDir . '/' . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }
    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO posts (title, slug, content, image, category_id) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo '<div style="color:red;">Gagal prepare query: ' . $conn->error . '</div>';
        exit;
    }
    $stmt->bind_param('ssssi', $title, $slug, $content, $image, $category_id);
    if (!$stmt->execute()) {
        echo '<div style="color:red;">Gagal insert: ' . $stmt->error . '</div>';
        exit;
    }
    header('Location: admin_dashboard.php?success=1');
    exit;
}

// Proses hapus berita jika form delete disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_news_id'])) {
    $delete_id = intval($_POST['delete_news_id']);
    // Hapus gambar jika ada
    $imgres = $conn->query("SELECT image FROM posts WHERE id = $delete_id");
    if ($imgrow = $imgres->fetch_assoc()) {
        if (!empty($imgrow['image'])) {
            $imgfile = __DIR__ . '/images/' . $imgrow['image'];
            if (file_exists($imgfile)) unlink($imgfile);
        }
    }
    $conn->query("DELETE FROM posts WHERE id = $delete_id");
    header('Location: admin_dashboard.php?deleted=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - IndomiEggNews</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vast+Shadow&display=swap" rel="stylesheet">
    <script src="js/admin.js" defer></script>
</head>
<body>
    <div class="header">
        <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
        <div class="logo">IndomiEggNews</div>
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="admin_dashboard.php">Admin Page</a>
            <form method="post" action="logout.php" class="logout-inline">
                <button class="logout-btn" type="submit">Logout</button>
            </form>
        </div>
    </div>
    <div class="container">
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
                // Pilih warna box kategori
                $catColors = [
                    0 => '#ffe082', // kuning
                    1 => '#b3e5fc', // biru muda
                    2 => '#c8e6c9', // hijau muda
                    3 => '#ffd180', // oranye muda
                    4 => '#f8bbd0', // pink muda
                    5 => '#d1c4e9', // ungu muda
                    6 => '#fff9c4', // kuning pucat
                    7 => '#b2dfdb', // toska muda
                    8 => '#ffccbc', // peach
                    9 => '#e1bee7', // ungu pink
                ];
                $colorIdx = $cat_id % count($catColors);
                $catBoxStyle = "background:{$catColors[$colorIdx]};color:#222;border-radius:16px;padding:0.18em 1.1em;font-weight:600;display:inline-block;margin-bottom:0.2em;min-width:80px;text-align:center;border:1.5px solid #eee;";
                echo "<li class='sidebar-cat-item'>";
                echo "<a href='javascript:void(0)' class='sidebar-cat-link' data-cat='$cat_id' style='$catBoxStyle'>".htmlspecialchars($cat_name)."</a>";
                // Judul berita per kategori, hidden by default
                echo "<div class='sidebar-news-list' id='cat-news-$cat_id'>";
                $newsres = $conn->query("SELECT slug, title FROM posts WHERE category_id = $cat_id ORDER BY id DESC LIMIT 20");
                if ($newsres->num_rows > 0) {
                    while($news = $newsres->fetch_assoc()) {
                        echo "<a class='news-title-link sidebar-news-title-ellipsis' href='news.php?slug=".urlencode($news['slug'])."' title='".htmlspecialchars($news['title'])."'>".mb_strimwidth(htmlspecialchars($news['title']),0,40,'...')."</a>";
                    }
                } else if ($isFinance) {
                    // Contoh dummy jika kategori finance belum ada berita
                    echo "<span class='sidebar-news-title-ellipsis' style='color:#aaa;'>Belum ada berita Finance</span>";
                }
                echo "</div>";
                echo "</li>";
            }
            // Jika belum ada kategori finance di database, tampilkan dummy visual
            if (!$hasFinance) {
                echo "<li class='sidebar-cat-item'>";
                echo "<span class='sidebar-cat-link log-cat-box' style='cursor:default;'>Finance</span>";
                echo "<div class='sidebar-news-list'><span class='sidebar-news-title-ellipsis' style='color:#aaa;'>Belum ada berita Finance</span></div>";
                echo "</li>";
            }
            ?>
            </ul>
        </div>
        <div class="sidebar-backdrop" onclick="closeSidebar()"></div>
        <div class="content admin-box" style="display: flex; gap: 2em;">
            <div style="flex:2;">
                <div class="admin-header">
                    <div class="admin-title">Admin Dashboard</div>
                </div>
                <div class="admin-actions" style="display:none;">
                    <!-- Tombol admin disembunyikan, semua menu admin masuk ke sidebar -->
                </div>
                <div class="admin-card add-news-card">
                    <form method="post" enctype="multipart/form-data" class="add-news-form" id="addNewsForm">
                        <input type="hidden" name="add_news" value="1">
                        <label>Judul Berita</label>
                        <input type="text" name="title" id="newsTitle" required oninput="updateSlugPreview()">
                        <div class="slug-preview">Slug: <span id="slugPreview">-</span></div>
                        <label>Kategori</label>
                        <select name="category" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php
                            $catres = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
                            while($cat = $catres->fetch_assoc()) {
                                $selected = (isset($_GET['category']) && $_GET['category'] == $cat['name']) ? 'selected' : '';
                                echo "<option value=\"{$cat['id']}\" $selected>".htmlspecialchars($cat['name'])."</option>";
                            }
                            ?>
                        </select>
                        <label>Isi Berita</label>
                        <textarea name="content" id="newsContent" rows="6" required></textarea>
                        <div style="margin-bottom:1em;">
                            <button type="button" id="saveContentBtn" style="background:#1976d2;color:#fff;border:none;padding:0.7em 2em;border-radius:4px;font-weight:bold;">Simpan Isi Berita</button>
                            <span id="contentSavedMsg" style="color:green;display:none;margin-left:1em;">Isi berita sudah disimpan!</span>
                        </div>
                        <label>Gambar (opsional)</label>
                        <input type="file" name="image" accept="image/*">
                    </form>
                    <div style="margin-top:1.5em;text-align:right;">
                        <button type="button" id="submitNewsBtn" disabled style="background:#ffeb3b;color:#222;border:none;padding:0.7em 2em;border-radius:4px;font-weight:bold;">Tambah Berita</button>
                    </div>
                </div>
                <div class="admin-card add-category-card">
                    <form method="post" action="add_category.php" class="add-category-form">
                        <label>Nama Kategori Baru</label>
                        <input type="text" name="category" required>
                        <button type="submit">Tambah Kategori</button>
                    </form>
                </div>
                <div class="admin-card list-news-card">
                    <h3>Daftar Berita</h3>
                    <table class="admin-news-table" style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f7f7f7;">
                                <th style="text-align:left;padding:8px;">Judul</th>
                                <th style="text-align:left;padding:8px;">Kategori</th>
                                <th style="text-align:left;padding:8px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $newslist = $conn->query("SELECT posts.id, posts.title, categories.name as category_name FROM posts LEFT JOIN categories ON posts.category_id = categories.id ORDER BY posts.id DESC LIMIT 30");
                        while($n = $newslist->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td style="padding:8px;">'.htmlspecialchars($n['title']).'</td>';
                            echo '<td style="padding:8px;">'.htmlspecialchars($n['category_name']).'</td>';
                            echo '<td style="padding:8px;">';
                            echo '<form method="post" style="display:inline;" onsubmit="return confirm(\'Yakin hapus berita ini?\');">';
                            echo '<input type="hidden" name="delete_news_id" value="'.$n['id'].'">';
                            echo '<button type="submit" class="delete-news-btn" style="background:#c00;color:#fff;border:none;padding:6px 16px;border-radius:4px;cursor:pointer;">Delete</button>';
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="flex:1; min-width:260px;">
                <div class="admin-card log-card">
                    <h3>Log Berita Terbaru</h3>
                    <div class="log-list">
                    <?php
                    $logres = $conn->query("SELECT posts.created_at, posts.title, categories.name as category_name FROM posts LEFT JOIN categories ON posts.category_id = categories.id ORDER BY posts.created_at DESC, posts.id DESC LIMIT 10");
                    if ($logres && $logres->num_rows > 0) {
                        while($log = $logres->fetch_assoc()) {
                            $waktu = date('l, d F Y H:i', strtotime($log['created_at']));
                            echo '<div class="log-item">';
                            echo '<div class="log-title" title="'.htmlspecialchars($log['title']).'">'.mb_strimwidth(htmlspecialchars($log['title']), 0, 40, '...').'</div>';
                            echo '<div class="log-meta"><span class="log-date-box">'.$waktu.'</span><span class="log-cat-box">'.htmlspecialchars($log['category_name']).'</span></div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="log-empty">Belum ada log berita.</div>';
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="admin-footer-logo">IndomiEggNews</div>
        <div>Kontak: info@indomieggnews.com | Telp: 0812-3456-7890</div>
        <div class="admin-footer-copyright">&copy; <?php echo date('Y'); ?> IndomiEggNews</div>
    </div>
    <script>
        function updateSlugPreview() {
            var title = document.getElementById('newsTitle').value;
            var slug = title.toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-|-$/g, '');
            document.getElementById('slugPreview').innerText = slug;
        }
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var saveBtn = document.getElementById('saveContentBtn');
        var submitBtn = document.getElementById('submitNewsBtn');
        var contentField = document.getElementById('newsContent');
        var contentSavedMsg = document.getElementById('contentSavedMsg');
        var form = document.getElementById('addNewsForm');
        var contentSaved = false;
        if (saveBtn && submitBtn && contentField && form) {
            saveBtn.addEventListener('click', function() {
                if (window.tinymce && tinymce.get('newsContent')) {
                    tinymce.get('newsContent').getBody().setAttribute('contenteditable', false);
                    tinymce.get('newsContent').getContainer().style.pointerEvents = 'none';
                } else {
                    contentField.readOnly = true;
                }
                contentSaved = true;
                submitBtn.disabled = false;
                saveBtn.disabled = true;
                contentSavedMsg.style.display = 'inline';
            });
            submitBtn.addEventListener('click', function() {
                if (!contentSaved) {
                    alert('Silakan klik "Simpan Isi Berita" terlebih dahulu sebelum menambah berita.');
                    return;
                }
                form.submit();
            });
        }
        // Cegah submit jika belum save
        document.getElementById('addNewsForm').addEventListener('submit', function(e) {
            if (!contentSaved) {
                alert('Silakan klik "Simpan Isi Berita" terlebih dahulu sebelum menambah berita.');
                e.preventDefault();
                return false;
            }
        });
        // Sidebar kategori toggle judul berita
        const catLinks = document.querySelectorAll('.sidebar-cat-link');
        catLinks.forEach(link => {
          link.addEventListener('click', function() {
            const catId = this.getAttribute('data-cat');
            document.querySelectorAll('.sidebar-news-list').forEach(list => {
              if (list.id === 'cat-news-' + catId) {
                list.style.display = (list.style.display === 'block') ? 'none' : 'block';
              } else {
                list.style.display = 'none';
              }
            });
          });
        });
    });
    </script>
</body>
</html>
