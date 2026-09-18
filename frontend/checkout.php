<?php
/**
 * Checkout — Cipamilk E-Commerce
 */
$page_title = 'Checkout — Cipamilk';
require_once __DIR__ . '/../config/database.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'customer') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    redirect($base_url . '/frontend/login.php');
}

$id_customer = $_SESSION['id_customer'];

// Ambil data customer
$stmt_cust = $pdo->prepare("SELECT * FROM customers WHERE id_customer = ?");
$stmt_cust->execute([$id_customer]);
$customer = $stmt_cust->fetch();

// Ambil item keranjang
$stmt = $pdo->prepare("
    SELECT c.*, p.nama_produk, p.harga, p.gambar
    FROM cart c
    JOIN products p ON c.id_product = p.id_product
    WHERE c.id_customer = ?
");
$stmt->execute([$id_customer]);
$cart_items = $stmt->fetchAll();

if (count($cart_items) === 0) {
    $_SESSION['error'] = 'Keranjang Anda kosong.';
    redirect($base_url . '/frontend/keranjang.php');
}

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['harga'] * $item['jumlah'];
}

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-credit-card mr-2"></i> Checkout</h1>
        <ul class="breadcrumb-cipamilk">
            <li><a href="<?= $base_url ?>/frontend/index.php">Home</a></li>
            <li class="separator"><i class="fas fa-chevron-right"></i></li>
            <li><a href="<?= $base_url ?>/frontend/keranjang.php">Keranjang</a></li>
            <li class="separator"><i class="fas fa-chevron-right"></i></li>
            <li class="current">Checkout</li>
        </ul>
    </div>
</div>

<section class="section-padding">
    <div class="container">
        <form action="<?= $base_url ?>/frontend/proses/proses_checkout.php" method="POST">
            <div class="row">
                <!-- Data Pelanggan -->
                <div class="col-lg-7 mb-4">
                    <div class="checkout-form-card">
                        <h4><i class="fas fa-user mr-2" style="color: var(--primary);"></i> Data Pengiriman</h4>
                        
                        <div class="form-group">
                            <label class="form-label-cipamilk">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-cipamilk" value="<?= htmlspecialchars($_SESSION['nama']) ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label class="form-label-cipamilk">Alamat Pengiriman</label>
                            <textarea name="alamat" class="form-control form-control-cipamilk" rows="3" required placeholder="Masukkan alamat lengkap pengiriman"><?= htmlspecialchars($customer['alamat'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label-cipamilk">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" class="form-control form-control-cipamilk" value="<?= htmlspecialchars($customer['nomor_telepon'] ?? '') ?>" required placeholder="08xxxxxxxxxx">
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label-cipamilk">Catatan (opsional)</label>
                            <textarea name="catatan" class="form-control form-control-cipamilk" rows="2" placeholder="Catatan untuk pesanan Anda"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Pesanan -->
                <div class="col-lg-5">
                    <div class="cart-summary">
                        <h4><i class="fas fa-receipt mr-2" style="color: var(--primary);"></i> Ringkasan Pesanan</h4>

                        <?php foreach ($cart_items as $item): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid var(--border);">
                            <div class="d-flex align-items-center">
                                <img src="<?= $base_url ?>/assets/images/products/<?= htmlspecialchars($item['gambar']) ?>" 
                                     class="cart-item-image mr-3" alt=""
                                     onerror="this.style.display='none'">
                                <div>
                                    <strong style="font-family: var(--font-heading); font-size: 0.9rem;"><?= htmlspecialchars($item['nama_produk']) ?></strong>
                                    <br><small class="text-muted">x<?= $item['jumlah'] ?></small>
                                </div>
                            </div>
                            <span style="font-weight: 600;"><?= formatRupiah($item['harga'] * $item['jumlah']) ?></span>
                        </div>
                        <?php endforeach; ?>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span><?= formatRupiah($total) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Ongkos Kirim</span>
                            <span style="color: var(--green); font-weight: 600;">Gratis</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total Pembayaran</strong>
                            <span class="cart-summary-total"><?= formatRupiah($total) ?></span>
                        </div>

                        <button type="submit" class="btn btn-primary-cipamilk btn-block btn-lg">
                            <i class="fas fa-check-circle mr-2"></i> Buat Pesanan
                        </button>

                        <p class="text-center text-muted mt-3 mb-0" style="font-size: 0.8rem;">
                            <i class="fas fa-shield-alt mr-1"></i> Pembayaran aman & terpercaya
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
