<?php
/**
 * Admin Dashboard — Cipamilk E-Commerce
 */
$page_title = 'Dashboard — Admin Cipamilk';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

// Statistik
$total_produk      = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_customer    = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$total_pesanan     = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_subscription = $pdo->query("SELECT COUNT(*) FROM subscriptions WHERE status = 'aktif'")->fetchColumn();
$total_pendapatan  = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'dibatalkan'")->fetchColumn();
$pesanan_pending   = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();

// Pesanan terbaru
$recent_orders = $pdo->query("
    SELECT o.*, c.id_user, u.nama 
    FROM orders o 
    JOIN customers c ON o.id_customer = c.id_customer 
    JOIN users u ON c.id_user = u.id_user 
    ORDER BY o.tanggal DESC LIMIT 5
")->fetchAll();
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight: 800;">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Stat Boxes -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box" style="background: linear-gradient(135deg, #4DA8DA, #3A8BBD); color: #fff;">
                        <div class="inner">
                            <h3><?= $total_produk ?></h3>
                            <p>Total Produk</p>
                        </div>
                        <div class="icon"><i class="fas fa-box-open"></i></div>
                        <a href="<?= $base_url ?>/admin/produk.php" class="small-box-footer" style="color: rgba(255,255,255,0.8);">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box" style="background: linear-gradient(135deg, #4F772D, #6B9B3E); color: #fff;">
                        <div class="inner">
                            <h3><?= $total_customer ?></h3>
                            <p>Pelanggan</p>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <a href="<?= $base_url ?>/admin/customer.php" class="small-box-footer" style="color: rgba(255,255,255,0.8);">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box" style="background: linear-gradient(135deg, #F4C95D, #E0B544); color: #333;">
                        <div class="inner">
                            <h3><?= $total_pesanan ?></h3>
                            <p>Total Pesanan</p>
                        </div>
                        <div class="icon"><i class="fas fa-shopping-bag"></i></div>
                        <a href="<?= $base_url ?>/admin/pesanan.php" class="small-box-footer" style="color: rgba(0,0,0,0.6);">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box" style="background: linear-gradient(135deg, #E74C3C, #C0392B); color: #fff;">
                        <div class="inner">
                            <h3><?= $total_subscription ?></h3>
                            <p>Subscription Aktif</p>
                        </div>
                        <div class="icon"><i class="fas fa-calendar-check"></i></div>
                        <a href="<?= $base_url ?>/admin/subscription.php" class="small-box-footer" style="color: rgba(255,255,255,0.8);">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Extra Stats -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff;">
                            <h3 class="card-title" style="font-weight: 700;"><i class="fas fa-money-bill-wave mr-2"></i> Ringkasan</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #eee;">
                                <span><i class="fas fa-wallet mr-2 text-success"></i> Total Pendapatan</span>
                                <strong style="font-size: 1.2rem; color: var(--green);"><?= formatRupiah($total_pendapatan) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #eee;">
                                <span><i class="fas fa-clock mr-2 text-warning"></i> Pesanan Pending</span>
                                <span class="badge badge-warning" style="font-size: 0.9rem; padding: 0.4rem 0.8rem;"><?= $pesanan_pending ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-calendar-check mr-2 text-info"></i> Subscription Aktif</span>
                                <span class="badge badge-info" style="font-size: 0.9rem; padding: 0.4rem 0.8rem;"><?= $total_subscription ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff;">
                            <h3 class="card-title" style="font-weight: 700;"><i class="fas fa-history mr-2"></i> Pesanan Terbaru</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>No. Pesanan</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_orders as $order): ?>
                                    <tr>
                                        <td><strong>#ORD-<?= str_pad($order['id_order'], 5, '0', STR_PAD_LEFT) ?></strong></td>
                                        <td><?= htmlspecialchars($order['nama']) ?></td>
                                        <td><?= formatRupiah($order['total']) ?></td>
                                        <td>
                                            <span class="badge badge-<?= $order['status'] ?>" style="padding: 0.3rem 0.6rem; border-radius: 20px; font-size: 0.75rem;">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($recent_orders)): ?>
                                    <tr><td colspan="4" class="text-center text-muted">Belum ada pesanan</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
