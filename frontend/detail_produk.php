<?php
/**
 * Detail Produk — Cipamilk E-Commerce
 */
require_once __DIR__ . '/../config/database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ambil data produk
$stmt = $pdo->prepare("SELECT p.*, c.nama_kategori FROM products p JOIN categories c ON p.id_category = c.id_category WHERE p.id_product = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    $_SESSION['error'] = 'Produk tidak ditemukan.';
    redirect($base_url . '/frontend/produk.php');
}

$page_title = htmlspecialchars($product['nama_produk']) . ' — Cipamilk';

// Produk terkait
$stmt_related = $pdo->prepare("SELECT p.*, c.nama_kategori FROM products p JOIN categories c ON p.id_category = c.id_category WHERE p.id_category = ? AND p.id_product != ? LIMIT 4");
$stmt_related->execute([$product['id_category'], $id]);
$related = $stmt_related->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1><?= htmlspecialchars($product['nama_produk']) ?></h1>
        <ul class="breadcrumb-cipamilk">
            <li><a href="<?= $base_url ?>/frontend/index.php">Home</a></li>
            <li class="separator"><i class="fas fa-chevron-right"></i></li>
            <li><a href="<?= $base_url ?>/frontend/produk.php">Produk</a></li>
            <li class="separator"><i class="fas fa-chevron-right"></i></li>
            <li class="current"><?= htmlspecialchars($product['nama_produk']) ?></li>
        </ul>
    </div>
</div>

<section class="section-padding">
    <div class="container">
        <div class="row">
            <!-- Product Image -->
            <div class="col-lg-5 mb-4">
                <div class="detail-image-wrapper">
                    <img src="<?= $base_url ?>/assets/images/products/<?= htmlspecialchars($product['gambar']) ?>"
                        alt="<?= htmlspecialchars($product['nama_produk']) ?>">
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-7">
                <div class="detail-info">
                    <span class="detail-category"><?= htmlspecialchars($product['nama_kategori']) ?></span>
                    <h1 class="detail-title"><?= htmlspecialchars($product['nama_produk']) ?></h1>
                    <div class="detail-price"><?= formatRupiah($product['harga']) ?></div>

                    <div class="detail-stock <?= $product['stok'] > 0 ? 'available' : 'empty' ?>">
                        <i class="fas fa-<?= $product['stok'] > 0 ? 'check-circle' : 'times-circle' ?>"></i>
                        <?= $product['stok'] > 0 ? 'Tersedia — Stok: ' . $product['stok'] : 'Stok Habis' ?>
                    </div>

                    <div class="detail-desc">
                        <?= nl2br(htmlspecialchars($product['deskripsi'])) ?>
                    </div>

                    <?php if ($product['stok'] > 0): ?>
                        <form action="<?= $base_url ?>/frontend/proses/proses_keranjang.php" method="POST"
                            id="form-add-to-cart">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="id_product" value="<?= $product['id_product'] ?>">

                            <div class="detail-qty">
                                <label>Jumlah:</label>
                                <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-outline-cipamilk btn-sm qty-minus"
                                        style="padding: 0.3rem 0.8rem;">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" name="jumlah" value="1" min="1" max="<?= $product['stok'] ?>"
                                        class="qty-input mx-2">
                                    <button type="button" class="btn btn-outline-cipamilk btn-sm qty-plus"
                                        style="padding: 0.3rem 0.8rem;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex gap-2 flex-wrap" style="gap: 0.8rem;">
                                <button type="submit" class="btn btn-primary-cipamilk" id="btn-add-to-cart">
                                    <i class="fas fa-cart-plus mr-2"></i> Tambah ke Keranjang
                                </button>
                                <a href="<?= $base_url ?>/frontend/subscription.php?produk=<?= $product['id_product'] ?>"
                                    class="btn btn-accent-cipamilk">
                                    <i class="fas fa-calendar-check mr-2"></i> Subscribe
                                </a>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert-cipamilk alert-warning-cipamilk">
                            <i class="fas fa-exclamation-triangle"></i>
                            Maaf, produk ini sedang habis. Silakan cek kembali nanti.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (count($related) > 0): ?>
            <div class="mt-5">
                <h3 class="section-title">Produk <span>Terkait</span></h3>
                <p class="section-subtitle">Produk lain dari kategori <?= htmlspecialchars($product['nama_kategori']) ?></p>
                <div class="row">
                    <?php foreach ($related as $r): ?>
                        <div class="col-lg-3 col-md-6 mb-4 reveal-on-scroll">
                            <div class="product-card"
                                data-href="<?= $base_url ?>/frontend/detail_produk.php?id=<?= $r['id_product'] ?>"
                                onclick="if(!event.target.closest('a, button')) window.location.href='<?= $base_url ?>/frontend/detail_produk.php?id=<?= $r['id_product'] ?>'">
                                <div class="product-card-image">
                                    <img src="<?= $base_url ?>/assets/images/products/<?= htmlspecialchars($r['gambar']) ?>"
                                        alt="<?= htmlspecialchars($r['nama_produk']) ?>">
                                </div>
                                <div class="product-card-body">
                                    <span class="product-card-category"><?= htmlspecialchars($r['nama_kategori']) ?></span>
                                    <h5 class="product-card-title"><?= htmlspecialchars($r['nama_produk']) ?></h5>
                                    <div class="product-card-footer">
                                        <span class="product-card-price"><?= formatRupiah($r['harga']) ?></span>
                                        <a href="<?= $base_url ?>/frontend/detail_produk.php?id=<?= $r['id_product'] ?>"
                                            class="btn btn-primary-cipamilk btn-sm">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>