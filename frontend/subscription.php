<?php
/**
 * Subscription — Cipamilk E-Commerce
 */
$page_title = 'Subscription — Cipamilk';
require_once __DIR__ . '/../config/database.php';

// Ambil semua produk untuk pilihan
$products = $pdo->query("SELECT * FROM products WHERE stok > 0 ORDER BY nama_produk")->fetchAll();

// Cek subscription aktif jika login
$my_subscriptions = [];
if (isLoggedIn() && $_SESSION['role'] === 'customer') {
    $stmt = $pdo->prepare("
        SELECT s.*, p.nama_produk, p.harga, p.gambar
        FROM subscriptions s
        JOIN products p ON s.id_product = p.id_product
        WHERE s.id_customer = ?
        ORDER BY s.created_at DESC
    ");
    $stmt->execute([$_SESSION['id_customer']]);
    $my_subscriptions = $stmt->fetchAll();
}

// Pre-select produk dari query param
$selected_product = isset($_GET['produk']) ? (int)$_GET['produk'] : 0;

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-calendar-check mr-2"></i> Subscription</h1>
        <p>Langganan produk susu segar dan dapatkan kemudahan setiap hari</p>
        <ul class="breadcrumb-cipamilk">
            <li><a href="<?= $base_url ?>/frontend/index.php">Home</a></li>
            <li class="separator"><i class="fas fa-chevron-right"></i></li>
            <li class="current">Subscription</li>
        </ul>
    </div>
</div>

<!-- Subscription Plans -->
<section class="section-padding section-cream">
    <div class="container">
        <h2 class="section-title">Paket <span>Subscription</span> Cipamilk</h2>
        <p class="section-subtitle">Pilih periode langganan yang sesuai dengan kebutuhan Anda</p>

        <div class="row justify-content-center">
            <!-- Harian -->
            <div class="col-lg-4 col-md-6 mb-4 reveal-on-scroll">
                <div class="subscription-card">
                    <div class="subscription-card-icon">
                        <i class="fas fa-sun"></i>
                    </div>
                    <h4 class="subscription-card-title">Harian</h4>
                    <p class="subscription-card-period">Pengiriman setiap hari</p>
                    <p class="text-muted mb-3">Dapatkan susu segar setiap pagi langsung ke rumah Anda</p>
                    <ul class="list-unstyled text-left mb-3" style="font-size: 0.9rem;">
                        <li class="mb-2"><i class="fas fa-check-circle mr-2" style="color: var(--green);"></i> Pengiriman setiap hari</li>
                        <li class="mb-2"><i class="fas fa-check-circle mr-2" style="color: var(--green);"></i> Produk segar terjamin</li>
                        <li class="mb-2"><i class="fas fa-check-circle mr-2" style="color: var(--green);"></i> Bisa berhenti kapan saja</li>
                    </ul>
                </div>
            </div>

            <!-- Mingguan -->
            <div class="col-lg-4 col-md-6 mb-4 reveal-on-scroll" style="transition-delay: 0.1s">
                <div class="subscription-card featured">
                    <div class="subscription-card-icon" style="background: linear-gradient(135deg, var(--accent), var(--accent-dark));">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <h4 class="subscription-card-title">Mingguan</h4>
                    <p class="subscription-card-period">Pengiriman setiap minggu</p>
                    <p class="text-muted mb-3">Paket paling populer! Pengiriman rutin setiap minggu</p>
                    <ul class="list-unstyled text-left mb-3" style="font-size: 0.9rem;">
                        <li class="mb-2"><i class="fas fa-check-circle mr-2" style="color: var(--green);"></i> Pengiriman tiap minggu</li>
                        <li class="mb-2"><i class="fas fa-check-circle mr-2" style="color: var(--green);"></i> Paling diminati pelanggan</li>
                        <li class="mb-2"><i class="fas fa-check-circle mr-2" style="color: var(--green);"></i> Bisa berhenti kapan saja</li>
                    </ul>
                </div>
            </div>

            <!-- Bulanan -->
            <div class="col-lg-4 col-md-6 mb-4 reveal-on-scroll" style="transition-delay: 0.2s">
                <div class="subscription-card">
                    <div class="subscription-card-icon" style="background: linear-gradient(135deg, var(--green), var(--green-light));">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h4 class="subscription-card-title">Bulanan</h4>
                    <p class="subscription-card-period">Pengiriman setiap bulan</p>
                    <p class="text-muted mb-3">Cocok untuk kebutuhan bulanan keluarga Anda</p>
                    <ul class="list-unstyled text-left mb-3" style="font-size: 0.9rem;">
                        <li class="mb-2"><i class="fas fa-check-circle mr-2" style="color: var(--green);"></i> Pengiriman tiap bulan</li>
                        <li class="mb-2"><i class="fas fa-check-circle mr-2" style="color: var(--green);"></i> Hemat biaya pengiriman</li>
                        <li class="mb-2"><i class="fas fa-check-circle mr-2" style="color: var(--green);"></i> Bisa berhenti kapan saja</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Form Subscription -->
<section class="section-padding section-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="checkout-form-card">
                    <h4><i class="fas fa-edit mr-2" style="color: var(--accent);"></i> Buat Langganan Baru</h4>

                    <style>
                        .checkout-form-card select.form-control-cipamilk,
                        .checkout-form-card input.form-control-cipamilk {
                            height: 48px !important;
                            min-height: 48px !important;
                            padding: 0.5rem 1rem !important;
                            font-size: 0.95rem !important;
                            line-height: 1.5 !important;
                            color: var(--text-dark) !important;
                            background-color: #FAFAFA !important;
                            border: 2px solid var(--border) !important;
                            border-radius: var(--radius-sm) !important;
                        }
                        .checkout-form-card select.form-control-cipamilk:focus,
                        .checkout-form-card input.form-control-cipamilk:focus {
                            border-color: var(--primary) !important;
                            background-color: #FFFFFF !important;
                            box-shadow: 0 0 0 3px rgba(77,168,218,0.15) !important;
                        }
                        .checkout-form-card select.form-control-cipamilk option {
                            padding: 10px 14px;
                            font-size: 0.95rem;
                            color: #2D3436;
                            background-color: #FFFFFF;
                        }
                    </style>

                    <?php if (!isLoggedIn()): ?>
                        <div class="alert-cipamilk alert-warning-cipamilk">
                            <i class="fas fa-exclamation-triangle"></i>
                            Silakan <a href="<?= $base_url ?>/frontend/login.php" style="font-weight: 700;">login</a> terlebih dahulu untuk membuat subscription.
                        </div>
                    <?php else: ?>
                        <form action="<?= $base_url ?>/frontend/proses/proses_subscription.php" method="POST">
                            <input type="hidden" name="action" value="create">
                            
                            <div class="form-group">
                                <label class="form-label-cipamilk font-weight-bold">Pilih Produk</label>
                                <select name="id_product" class="form-control form-control-cipamilk" required>
                                    <option value="">-- Pilih Produk yang Ingin Dilanggan --</option>
                                    <?php foreach ($products as $p): ?>
                                        <option value="<?= $p['id_product'] ?>" <?= $selected_product === (int)$p['id_product'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($p['nama_produk']) ?> — <?= formatRupiah($p['harga']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-cipamilk font-weight-bold">Periode Langganan</label>
                                        <select name="periode" class="form-control form-control-cipamilk" required>
                                            <option value="harian">Harian (Setiap Hari)</option>
                                            <option value="mingguan" selected>Mingguan (Setiap Minggu)</option>
                                            <option value="bulanan">Bulanan (Setiap Bulan)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-cipamilk font-weight-bold">Tanggal Mulai</label>
                                        <input type="date" name="tanggal_mulai" class="form-control form-control-cipamilk" required min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-accent-cipamilk btn-block py-2 font-weight-bold" style="font-size: 1rem; border-radius: var(--radius-sm);">
                                <i class="fas fa-calendar-check mr-2"></i> Mulai Langganan
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- My Subscriptions -->
<?php if (isLoggedIn() && count($my_subscriptions) > 0): ?>
<section class="section-padding section-cream">
    <div class="container">
        <h3 class="section-title">Langganan <span>Saya</span></h3>
        <p class="section-subtitle">Kelola subscription aktif Anda</p>

        <div class="row justify-content-center">
            <?php foreach ($my_subscriptions as $sub): ?>
            <div class="col-lg-6 mb-4">
                <div class="checkout-form-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 style="font-family: var(--font-heading); font-weight: 700; margin: 0;">
                                <?= htmlspecialchars($sub['nama_produk']) ?>
                            </h5>
                            <small class="text-muted">ID: #SUB-<?= str_pad($sub['id_subscription'], 4, '0', STR_PAD_LEFT) ?></small>
                        </div>
                        <span class="badge-status badge-<?= $sub['status'] ?>">
                            <?= ucfirst($sub['status']) ?>
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted"><i class="fas fa-sync-alt mr-1"></i> Periode</span>
                        <span style="font-weight: 600;"><?= ucfirst($sub['periode']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted"><i class="fas fa-calendar mr-1"></i> Tanggal Mulai</span>
                        <span style="font-weight: 600;"><?= date('d M Y', strtotime($sub['tanggal_mulai'])) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted"><i class="fas fa-tag mr-1"></i> Harga</span>
                        <span style="font-weight: 600; color: var(--primary);"><?= formatRupiah($sub['harga']) ?>/<?= $sub['periode'] === 'harian' ? 'hari' : ($sub['periode'] === 'mingguan' ? 'minggu' : 'bulan') ?></span>
                    </div>

                    <?php if ($sub['status'] === 'aktif'): ?>
                        <a href="<?= $base_url ?>/frontend/proses/proses_subscription.php?action=cancel&id=<?= $sub['id_subscription'] ?>" 
                           class="btn btn-sm" style="color: var(--danger); border: 1px solid var(--danger); border-radius: var(--radius-sm);"
                           onclick="return confirm('Yakin ingin membatalkan langganan ini?')">
                            <i class="fas fa-times mr-1"></i> Batalkan
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
