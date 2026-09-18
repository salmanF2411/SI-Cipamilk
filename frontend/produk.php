<?php
/**
 * Katalog Produk — Cipamilk E-Commerce
 */
$page_title = 'Produk — Cipamilk';
require_once __DIR__ . '/../config/database.php';

// Ambil kategori
$categories = $pdo->query("SELECT * FROM categories ORDER BY nama_kategori")->fetchAll();

// Filter kategori
$filter_category = isset($_GET['kategori']) ? (int) $_GET['kategori'] : 0;

// Ambil produk
if ($filter_category > 0) {
    $stmt = $pdo->prepare("SELECT p.*, c.nama_kategori FROM products p JOIN categories c ON p.id_category = c.id_category WHERE p.id_category = ? ORDER BY p.created_at DESC");
    $stmt->execute([$filter_category]);
} else {
    $stmt = $pdo->query("SELECT p.*, c.nama_kategori FROM products p JOIN categories c ON p.id_category = c.id_category ORDER BY p.created_at DESC");
}
$products = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-box-open mr-2"></i> Produk Kami</h1>
        <p>Temukan produk olahan susu segar berkualitas dari Cipamilk</p>
        <ul class="breadcrumb-cipamilk">
            <li><a href="<?= $base_url ?>/frontend/index.php">Home</a></li>
            <li class="separator"><i class="fas fa-chevron-right"></i></li>
            <li class="current">Produk</li>
        </ul>
    </div>
</div>

<section class="section-padding">
    <div class="container">
        <!-- Category Filter -->
        <div class="category-filter">
            <a href="<?= $base_url ?>/frontend/produk.php"
                class="filter-btn <?= $filter_category === 0 ? 'active' : '' ?>">
                <i class="fas fa-th mr-1"></i> Semua
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= $base_url ?>/frontend/produk.php?kategori=<?= $cat['id_category'] ?>"
                    class="filter-btn <?= $filter_category === (int) $cat['id_category'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['nama_kategori']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Products Grid -->
        <?php if (count($products) > 0): ?>
            <div class="row">
                <?php foreach ($products as $index => $p): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 reveal-on-scroll"
                        style="transition-delay: <?= ($index % 4) * 0.1 ?>s">
                        <div class="product-card"
                            data-href="<?= $base_url ?>/frontend/detail_produk.php?id=<?= $p['id_product'] ?>"
                            onclick="if(!event.target.closest('a, button')) window.location.href='<?= $base_url ?>/frontend/detail_produk.php?id=<?= $p['id_product'] ?>'">
                            <div class="product-card-image">
                                <img src="<?= $base_url ?>/assets/images/products/<?= htmlspecialchars($p['gambar']) ?>"
                                    alt="<?= htmlspecialchars($p['nama_produk']) ?>">
                                <?php if ($p['stok'] <= 0): ?>
                                    <span class="product-card-badge" style="background: var(--danger); color: #fff;">Habis</span>
                                <?php endif; ?>
                            </div>
                            <div class="product-card-body">
                                <span class="product-card-category"><?= htmlspecialchars($p['nama_kategori']) ?></span>
                                <h5 class="product-card-title"><?= htmlspecialchars($p['nama_produk']) ?></h5>
                                <p class="product-card-desc"><?= htmlspecialchars($p['deskripsi']) ?></p>
                                <div class="product-card-footer">
                                    <div>
                                        <span class="product-card-price"><?= formatRupiah($p['harga']) ?></span>
                                        <?php if ($p['stok'] <= 5 && $p['stok'] > 0): ?>
                                            <div class="text-warning" style="font-size: 0.75rem; font-weight: 600;">
                                                <i class="fas fa-exclamation-circle mr-1"></i> Sisa <?= $p['stok'] ?>
                                            </div>
                                        <?php elseif ($p['stok'] <= 0): ?>
                                            <div class="text-danger" style="font-size: 0.75rem; font-weight: 600;">
                                                <i class="fas fa-times-circle mr-1"></i> Habis
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?= $base_url ?>/frontend/detail_produk.php?id=<?= $p['id_product'] ?>"
                                        class="btn btn-primary-cipamilk btn-sm">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h4>Produk Tidak Ditemukan</h4>
                <p>Belum ada produk dalam kategori ini</p>
                <a href="<?= $base_url ?>/frontend/produk.php" class="btn btn-primary-cipamilk">
                    <i class="fas fa-th mr-2"></i> Lihat Semua Produk
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>