<?php
/**
 * Admin Laporan — Cipamilk E-Commerce
 */
$page_title = 'Laporan — Admin Cipamilk';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

// Statistik penjualan
$total_pendapatan = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'dibatalkan'")->fetchColumn();
$total_pesanan    = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pesanan_selesai  = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'selesai'")->fetchColumn();
$pesanan_batal    = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'dibatalkan'")->fetchColumn();

// Produk terlaris
$top_products = $pdo->query("
    SELECT p.nama_produk, SUM(od.jumlah) as total_terjual, SUM(od.subtotal) as total_penjualan
    FROM order_details od
    JOIN products p ON od.id_product = p.id_product
    JOIN orders o ON od.id_order = o.id_order
    WHERE o.status != 'dibatalkan'
    GROUP BY p.id_product
    ORDER BY total_terjual DESC
    LIMIT 5
")->fetchAll();

// Pesanan per status
$status_counts = $pdo->query("
    SELECT status, COUNT(*) as jumlah
    FROM orders
    GROUP BY status
")->fetchAll();

// Subscription per periode
$sub_periods = $pdo->query("
    SELECT periode, COUNT(*) as jumlah
    FROM subscriptions
    WHERE status = 'aktif'
    GROUP BY periode
")->fetchAll();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight: 800;"><i class="fas fa-chart-bar mr-2"></i> Laporan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $base_url ?>/admin/index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Laporan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Summary Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box" style="background: linear-gradient(135deg, #4DA8DA, #3A8BBD); color: #fff;">
                        <div class="inner">
                            <h3 style="font-size: 1.4rem;"><?= formatRupiah($total_pendapatan) ?></h3>
                            <p>Total Pendapatan</p>
                        </div>
                        <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box" style="background: linear-gradient(135deg, #F4C95D, #E0B544); color: #333;">
                        <div class="inner">
                            <h3><?= $total_pesanan ?></h3>
                            <p>Total Pesanan</p>
                        </div>
                        <div class="icon"><i class="fas fa-shopping-bag"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box" style="background: linear-gradient(135deg, #4F772D, #6B9B3E); color: #fff;">
                        <div class="inner">
                            <h3><?= $pesanan_selesai ?></h3>
                            <p>Pesanan Selesai</p>
                        </div>
                        <div class="icon"><i class="fas fa-check-circle"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box" style="background: linear-gradient(135deg, #E74C3C, #C0392B); color: #fff;">
                        <div class="inner">
                            <h3><?= $pesanan_batal ?></h3>
                            <p>Pesanan Dibatalkan</p>
                        </div>
                        <div class="icon"><i class="fas fa-times-circle"></i></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Produk Terlaris -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff;">
                            <h3 class="card-title" style="font-weight: 700;"><i class="fas fa-trophy mr-2"></i> Produk Terlaris</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Produk</th>
                                        <th>Terjual</th>
                                        <th>Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($top_products as $i => $tp): ?>
                                    <tr>
                                        <td>
                                            <?php if ($i === 0): ?>
                                                <i class="fas fa-medal" style="color: gold; font-size: 1.2rem;"></i>
                                            <?php elseif ($i === 1): ?>
                                                <i class="fas fa-medal" style="color: silver; font-size: 1.2rem;"></i>
                                            <?php elseif ($i === 2): ?>
                                                <i class="fas fa-medal" style="color: #cd7f32; font-size: 1.2rem;"></i>
                                            <?php else: ?>
                                                <?= $i + 1 ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?= htmlspecialchars($tp['nama_produk']) ?></strong></td>
                                        <td><span class="badge badge-info" style="border-radius: 20px; padding: 0.3rem 0.6rem;"><?= $tp['total_terjual'] ?></span></td>
                                        <td><strong style="color: var(--green);"><?= formatRupiah($tp['total_penjualan']) ?></strong></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($top_products)): ?>
                                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Status Pesanan & Subscription -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff;">
                            <h3 class="card-title" style="font-weight: 700;"><i class="fas fa-chart-pie mr-2"></i> Status Pesanan</h3>
                        </div>
                        <div class="card-body">
                            <?php foreach ($status_counts as $sc): ?>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>
                                    <span class="badge badge-<?= $sc['status'] ?>" style="padding: 0.3rem 0.6rem; border-radius: 20px; font-size: 0.8rem; min-width: 90px; display: inline-block; text-align: center;">
                                        <?= ucfirst($sc['status']) ?>
                                    </span>
                                </span>
                                <strong><?= $sc['jumlah'] ?> pesanan</strong>
                            </div>
                            <?php endforeach; ?>
                            <?php if (empty($status_counts)): ?>
                                <p class="text-muted text-center">Belum ada data</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, var(--accent), var(--accent-dark)); color: #333;">
                            <h3 class="card-title" style="font-weight: 700;"><i class="fas fa-calendar-check mr-2"></i> Subscription Aktif per Periode</h3>
                        </div>
                        <div class="card-body">
                            <?php foreach ($sub_periods as $sp): ?>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span style="font-weight: 600;"><?= ucfirst($sp['periode']) ?></span>
                                <strong><?= $sp['jumlah'] ?> subscriber</strong>
                            </div>
                            <?php endforeach; ?>
                            <?php if (empty($sub_periods)): ?>
                                <p class="text-muted text-center">Belum ada subscription aktif</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
