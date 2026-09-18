<?php
/**
 * Keranjang Belanja — Cipamilk E-Commerce
 */
$page_title = 'Keranjang — Cipamilk';
require_once __DIR__ . '/../config/database.php';

// Harus login
if (!isLoggedIn() || $_SESSION['role'] !== 'customer') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    redirect($base_url . '/frontend/login.php');
}

$id_customer = $_SESSION['id_customer'];

// Ambil item keranjang
$stmt = $pdo->prepare("
    SELECT c.*, p.nama_produk, p.harga, p.gambar, p.stok, cat.nama_kategori
    FROM cart c
    JOIN products p ON c.id_product = p.id_product
    JOIN categories cat ON p.id_category = cat.id_category
    WHERE c.id_customer = ?
");
$stmt->execute([$id_customer]);
$cart_items = $stmt->fetchAll();

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['harga'] * $item['jumlah'];
}

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-shopping-cart mr-2"></i> Keranjang Belanja</h1>
        <ul class="breadcrumb-cipamilk">
            <li><a href="<?= $base_url ?>/frontend/index.php">Home</a></li>
            <li class="separator"><i class="fas fa-chevron-right"></i></li>
            <li class="current">Keranjang</li>
        </ul>
    </div>
</div>

<section class="section-padding">
    <div class="container">
        <?php if (count($cart_items) > 0): ?>
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8 mb-4">
                <div class="cart-table">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= $base_url ?>/assets/images/products/<?= htmlspecialchars($item['gambar']) ?>" 
                                             alt="<?= htmlspecialchars($item['nama_produk']) ?>" class="cart-item-image mr-3"
                                             onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><rect fill=%22%23f0f0f0%22 width=%2260%22 height=%2260%22/></svg>'">
                                        <div>
                                            <strong style="font-family: var(--font-heading);"><?= htmlspecialchars($item['nama_produk']) ?></strong>
                                            <br><small class="text-muted"><?= htmlspecialchars($item['nama_kategori']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= formatRupiah($item['harga']) ?></td>
                                <td>
                                    <form action="<?= $base_url ?>/frontend/proses/proses_keranjang.php" method="POST" class="d-flex align-items-center">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="id_cart" value="<?= $item['id_cart'] ?>">
                                        <button type="button" class="btn btn-sm btn-outline-cipamilk qty-minus" style="padding: 0.2rem 0.5rem; font-size: 0.8rem;">-</button>
                                        <input type="number" name="jumlah" value="<?= $item['jumlah'] ?>" min="1" max="<?= $item['stok'] ?>" class="qty-input mx-1" onchange="this.form.submit()">
                                        <button type="button" class="btn btn-sm btn-outline-cipamilk qty-plus" style="padding: 0.2rem 0.5rem; font-size: 0.8rem;">+</button>
                                    </form>
                                </td>
                                <td><strong><?= formatRupiah($item['harga'] * $item['jumlah']) ?></strong></td>
                                <td>
                                    <a href="<?= $base_url ?>/frontend/proses/proses_keranjang.php?action=delete&id_cart=<?= $item['id_cart'] ?>" 
                                       class="btn btn-sm" style="color: var(--danger);" onclick="return confirm('Hapus item ini?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="cart-summary">
                    <h4><i class="fas fa-receipt mr-2" style="color: var(--primary);"></i> Ringkasan</h4>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Item</span>
                        <span><?= count($cart_items) ?> produk</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal</span>
                        <span><?= formatRupiah($total) ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total</strong>
                        <span class="cart-summary-total"><?= formatRupiah($total) ?></span>
                    </div>

                    <a href="<?= $base_url ?>/frontend/checkout.php" class="btn btn-primary-cipamilk btn-block">
                        <i class="fas fa-credit-card mr-2"></i> Checkout
                    </a>
                    <a href="<?= $base_url ?>/frontend/produk.php" class="btn btn-outline-cipamilk btn-block mt-2">
                        <i class="fas fa-arrow-left mr-2"></i> Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-shopping-cart"></i>
                <h4>Keranjang Kosong</h4>
                <p>Anda belum menambahkan produk ke keranjang</p>
                <a href="<?= $base_url ?>/frontend/produk.php" class="btn btn-primary-cipamilk">
                    <i class="fas fa-box-open mr-2"></i> Mulai Belanja
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
