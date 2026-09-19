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
                            <input type="text" class="form-control form-control-cipamilk"
                                value="<?= htmlspecialchars($_SESSION['nama']) ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label class="form-label-cipamilk">Alamat Pengiriman</label>
                            <textarea name="alamat" class="form-control form-control-cipamilk" rows="3" required
                                placeholder="Masukkan alamat lengkap pengiriman"><?= htmlspecialchars($customer['alamat'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label-cipamilk">Nomor Telepon/WhatsApp</label>
                            <input type="text" name="nomor_telepon" class="form-control form-control-cipamilk"
                                value="<?= htmlspecialchars($customer['nomor_telepon'] ?? '') ?>" required
                                placeholder="08xxxxxxxxxx">
                        </div>

                        <div class="form-group">
                            <label class="form-label-cipamilk"><i class="fas fa-money-bill-wave mr-1"
                                    style="color: var(--primary);"></i> Metode Pembayaran</label>
                            <div class="payment-method-options">
                                <!-- COD Option -->
                                <label class="payment-option" id="payment-cod-label">
                                    <input type="radio" name="metode_pembayaran" value="cod" checked id="payment-cod">
                                    <div class="payment-option-card">
                                        <div class="payment-option-header">
                                            <i class="fas fa-hand-holding-usd"
                                                style="font-size: 1.3rem; color: #27ae60;"></i>
                                            <div>
                                                <strong>Bayar di Tempat (COD)</strong>
                                                <small class="d-block text-muted">Bayar tunai saat pesanan
                                                    diterima</small>
                                            </div>
                                        </div>
                                        <div class="payment-info-box" id="cod-info" style="display: block;">
                                            <i class="fas fa-info-circle mr-1" style="color: #f39c12;"></i>
                                            <span>Siapkan <strong>uang pas</strong> ketika pesanan diterima</span>
                                        </div>
                                    </div>
                                </label>

                                <!-- QRIS Option -->
                                <label class="payment-option" id="payment-qris-label">
                                    <input type="radio" name="metode_pembayaran" value="qris" id="payment-qris">
                                    <div class="payment-option-card">
                                        <div class="payment-option-header">
                                            <i class="fas fa-qrcode" style="font-size: 1.3rem; color: #8e44ad;"></i>
                                            <div>
                                                <strong>QRIS</strong>
                                                <small class="d-block text-muted">Scan QRIS untuk pembayaran
                                                    digital</small>
                                            </div>
                                        </div>
                                        <div class="payment-qris-detail" id="qris-detail" style="display: none;">
                                            <div class="qris-image-wrapper">
                                                <img src="<?= $base_url ?>/assets/images/uploads/Qris.jpeg"
                                                    alt="QRIS Cipamilk" class="qris-image">
                                            </div>
                                            <div class="payment-info-box"
                                                style="background: #f3e8ff; border-color: #d8b4fe;">
                                                <i class="fas fa-info-circle mr-1" style="color: #8e44ad;"></i>
                                                <span>Tunjukan <strong>bukti QRIS</strong> saat menerima pesanan</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label-cipamilk">Catatan (opsional)</label>
                            <textarea name="catatan" class="form-control form-control-cipamilk" rows="2"
                                placeholder="Catatan untuk pesanan Anda"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Pesanan -->
                <div class="col-lg-5">
                    <div class="cart-summary">
                        <h4><i class="fas fa-receipt mr-2" style="color: var(--primary);"></i> Ringkasan Pesanan</h4>

                        <?php foreach ($cart_items as $item): ?>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3"
                                style="border-bottom: 1px solid var(--border);">
                                <div class="d-flex align-items-center">
                                    <img src="<?= $base_url ?>/assets/images/products/<?= htmlspecialchars($item['gambar']) ?>"
                                        class="cart-item-image mr-3" alt="" onerror="this.style.display='none'">
                                    <div>
                                        <strong
                                            style="font-family: var(--font-heading); font-size: 0.9rem;"><?= htmlspecialchars($item['nama_produk']) ?></strong>
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

<style>
    /* Payment Method Styles */
    .payment-method-options {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .payment-option {
        cursor: pointer;
        margin-bottom: 0;
    }

    .payment-option input[type="radio"] {
        display: none;
    }

    .payment-option-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        transition: all 0.25s ease;
        background: #fff;
    }

    .payment-option input[type="radio"]:checked+.payment-option-card {
        border-color: var(--primary);
        background: linear-gradient(135deg, rgba(79, 119, 45, 0.04), rgba(79, 119, 45, 0.08));
        box-shadow: 0 0 0 3px rgba(79, 119, 45, 0.12);
    }

    .payment-option-header {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .payment-option-header strong {
        font-size: 0.95rem;
        color: #1e293b;
    }

    .payment-info-box {
        margin-top: 10px;
        padding: 10px 14px;
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #92400e;
        display: flex;
        align-items: center;
    }

    .payment-qris-detail {
        margin-top: 12px;
    }

    .qris-image-wrapper {
        text-align: center;
        margin-bottom: 10px;
    }

    .qris-image {
        max-width: 220px;
        width: 100%;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const codRadio = document.getElementById('payment-cod');
        const qrisRadio = document.getElementById('payment-qris');
        const codInfo = document.getElementById('cod-info');
        const qrisDetail = document.getElementById('qris-detail');

        function togglePayment() {
            if (codRadio.checked) {
                codInfo.style.display = 'flex';
                qrisDetail.style.display = 'none';
            } else {
                codInfo.style.display = 'none';
                qrisDetail.style.display = 'block';
            }
        }

        codRadio.addEventListener('change', togglePayment);
        qrisRadio.addEventListener('change', togglePayment);
        togglePayment();
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>