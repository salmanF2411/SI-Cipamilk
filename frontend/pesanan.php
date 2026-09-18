<?php
/**
 * Riwayat Pesanan — Cipamilk E-Commerce
 */
$page_title = 'Pesanan Saya — Cipamilk';
require_once __DIR__ . '/../config/database.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'customer') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    redirect($base_url . '/frontend/login.php');
}

$id_customer = $_SESSION['id_customer'];

// Ambil pesanan
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id_customer = ? ORDER BY tanggal DESC");
$stmt->execute([$id_customer]);
$orders = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-receipt mr-2"></i> Pesanan Saya</h1>
        <ul class="breadcrumb-cipamilk">
            <li><a href="<?= $base_url ?>/frontend/index.php">Home</a></li>
            <li class="separator"><i class="fas fa-chevron-right"></i></li>
            <li class="current">Pesanan</li>
        </ul>
    </div>
</div>

<section class="section-padding">
    <div class="container">
        <?php if (count($orders) > 0): ?>
            <div class="order-table">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>
                                <strong style="font-family: var(--font-heading);">
                                    #ORD-<?= str_pad($order['id_order'], 5, '0', STR_PAD_LEFT) ?>
                                </strong>
                            </td>
                            <td><?= date('d M Y, H:i', strtotime($order['tanggal'])) ?></td>
                            <td><strong style="color: var(--primary);"><?= formatRupiah($order['total']) ?></strong></td>
                            <td>
                                <span class="badge-status badge-<?= $order['status'] ?>">
                                    <i class="fas fa-circle mr-1" style="font-size: 0.5rem;"></i>
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-primary-cipamilk btn-sm" data-toggle="modal" data-target="#orderModal<?= $order['id_order'] ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="orderModal<?= $order['id_order'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content" style="border-radius: var(--radius-md); border: none;">
                                    <div class="modal-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff; border-radius: var(--radius-md) var(--radius-md) 0 0;">
                                        <h5 class="modal-title" style="font-family: var(--font-heading); font-weight: 700;">
                                            <i class="fas fa-receipt mr-2"></i> Detail Pesanan #ORD-<?= str_pad($order['id_order'], 5, '0', STR_PAD_LEFT) ?>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding: 1.5rem;">
                                        <?php
                                        $stmt_detail = $pdo->prepare("
                                            SELECT od.*, p.nama_produk, p.gambar
                                            FROM order_details od
                                            JOIN products p ON od.id_product = p.id_product
                                            WHERE od.id_order = ?
                                        ");
                                        $stmt_detail->execute([$order['id_order']]);
                                        $details = $stmt_detail->fetchAll();
                                        ?>
                                        <?php foreach ($details as $d): ?>
                                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid var(--border);">
                                            <div>
                                                <strong style="font-family: var(--font-heading);"><?= htmlspecialchars($d['nama_produk']) ?></strong>
                                                <br><small class="text-muted">x<?= $d['jumlah'] ?></small>
                                            </div>
                                            <span style="font-weight: 600;"><?= formatRupiah($d['subtotal']) ?></span>
                                        </div>
                                        <?php endforeach; ?>
                                        
                                        <div class="d-flex justify-content-between mt-3">
                                            <strong style="font-size: 1.1rem;">Total</strong>
                                            <strong style="font-size: 1.1rem; color: var(--primary);"><?= formatRupiah($order['total']) ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-receipt"></i>
                <h4>Belum Ada Pesanan</h4>
                <p>Anda belum memiliki riwayat pesanan</p>
                <a href="<?= $base_url ?>/frontend/produk.php" class="btn btn-primary-cipamilk">
                    <i class="fas fa-box-open mr-2"></i> Mulai Belanja
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
