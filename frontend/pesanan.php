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

// Cek jika baru saja membuat pesanan
$order_success = $_SESSION['order_success'] ?? null;
if ($order_success) {
    unset($_SESSION['order_success']);
}

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
                            <tr
                                class="<?= ($order_success && $order_success['id_order'] == $order['id_order']) ? 'table-success' : '' ?>">
                                <td>
                                    <strong style="font-family: var(--font-heading);">
                                        #ORD-<?= str_pad($order['id_order'], 5, '0', STR_PAD_LEFT) ?>
                                    </strong>
                                    <?php if ($order_success && $order_success['id_order'] == $order['id_order']): ?>
                                        <span class="badge badge-success ml-2" style="font-size: 0.75rem;">Baru Dibuat</span>
                                    <?php endif; ?>
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
                                    <div class="d-inline-flex" style="gap: 6px;">
                                        <button class="btn btn-primary-cipamilk btn-sm" data-toggle="modal"
                                            data-target="#orderModal<?= $order['id_order'] ?>" title="Lihat Detail">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        <?php if ($order['status'] === 'dikirim'): ?>
                                            <a href="<?= $base_url ?>/frontend/proses/proses_pesanan.php?action=complete&id=<?= $order['id_order'] ?>"
                                                class="btn btn-sm btn-success"
                                                onclick="return confirm('Konfirmasi bahwa pesanan #ORD-<?= str_pad($order['id_order'], 5, '0', STR_PAD_LEFT) ?> sudah sampai dan Anda terima dengan baik?')"
                                                title="Konfirmasi Pesanan Diterima"
                                                style="border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600; padding: 0.35rem 0.75rem;">
                                                <i class="fas fa-check-circle mr-1"></i> Diterima
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Modals Detail Pesanan (Diletakkan di luar table agar markup HTML valid) -->
            <?php foreach ($orders as $order): ?>
                <div class="modal fade" id="orderModal<?= $order['id_order'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content"
                            style="border-radius: var(--radius-md); border: none; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.15);">
                            <div class="modal-header"
                                style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff;">
                                <h5 class="modal-title font-weight-bold"
                                    style="font-family: var(--font-heading); font-size: 1.1rem;">
                                    <i class="fas fa-receipt mr-2"></i> Detail Pesanan
                                    #ORD-<?= str_pad($order['id_order'], 5, '0', STR_PAD_LEFT) ?>
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.85;">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="padding: 1.5rem;">
                                <?php if (!empty($order['alamat_pengiriman'])): ?>
                                    <div class="p-3 mb-3"
                                        style="background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 0.88rem;">
                                        <div class="text-muted mb-1"><i class="fas fa-map-marker-alt mr-1"
                                                style="color: var(--primary);"></i> <strong>Alamat Pengiriman:</strong></div>
                                        <div><?= nl2br(htmlspecialchars($order['alamat_pengiriman'])) ?></div>
                                        <?php if (!empty($order['nomor_telepon'])): ?>
                                            <div class="mt-1 text-muted"><i class="fas fa-phone mr-1"></i>
                                                <?= htmlspecialchars($order['nomor_telepon']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Metode Pembayaran -->
                                <div class="p-3 mb-3"
                                    style="background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 0.88rem;">
                                    <div class="text-muted mb-2"><i class="fas fa-money-bill-wave mr-1"
                                            style="color: var(--primary);"></i> <strong>Metode Pembayaran:</strong></div>
                                    <?php if (($order['metode_pembayaran'] ?? 'cod') === 'qris'): ?>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge"
                                                style="background: rgba(142,68,173,0.15); color: #8e44ad; border: 1px solid rgba(142,68,173,0.3); padding: 5px 12px; border-radius: 20px; font-weight: 700; font-size: 0.8rem;">
                                                <i class="fas fa-qrcode mr-1"></i> QRIS
                                            </span>
                                        </div>
                                        <div style="text-align: center; margin: 8px 0;">
                                            <img src="<?= $base_url ?>/assets/images/uploads/Qris.jpeg" alt="QRIS"
                                                style="max-width: 160px; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                                        </div>
                                        <div style="font-size: 0.82rem; color: #8e44ad;">
                                            <i class="fas fa-info-circle mr-1"></i> Tunjukan bukti QRIS saat menerima pesanan
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge"
                                                style="background: rgba(39,174,96,0.15); color: #27ae60; border: 1px solid rgba(39,174,96,0.3); padding: 5px 12px; border-radius: 20px; font-weight: 700; font-size: 0.8rem;">
                                                <i class="fas fa-hand-holding-usd mr-1"></i> Bayar di Tempat (COD)
                                            </span>
                                        </div>
                                        <div style="font-size: 0.82rem; color: #92400e;">
                                            <i class="fas fa-info-circle mr-1" style="color: #f39c12;"></i> Siapkan uang pas ketika
                                            pesanan diterima
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <h6 class="font-weight-bold mb-3" style="font-size: 0.95rem; color: #4a5568;">Item Produk:</h6>
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
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3"
                                        style="border-bottom: 1px solid var(--border);">
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($d['gambar'])): ?>
                                                <img src="<?= $base_url ?>/assets/images/products/<?= htmlspecialchars($d['gambar']) ?>"
                                                    alt=""
                                                    style="width: 42px; height: 42px; object-fit: cover; border-radius: 8px; margin-right: 12px; border: 1px solid #e2e8f0;">
                                            <?php endif; ?>
                                            <div>
                                                <strong
                                                    style="font-family: var(--font-heading); font-size: 0.95rem;"><?= htmlspecialchars($d['nama_produk']) ?></strong>
                                                <br><small class="text-muted"><?= formatRupiah($d['subtotal'] / $d['jumlah']) ?>
                                                    &times; <?= $d['jumlah'] ?></small>
                                            </div>
                                        </div>
                                        <strong style="color: #2c3e50;"><?= formatRupiah($d['subtotal']) ?></strong>
                                    </div>
                                <?php endforeach; ?>

                                <div class="d-flex justify-content-between mt-3 pt-2" style="border-top: 2px dashed #e2e8f0;">
                                    <strong style="font-size: 1.05rem;">Total Pembayaran</strong>
                                    <strong
                                        style="font-size: 1.15rem; color: var(--primary);"><?= formatRupiah($order['total']) ?></strong>
                                </div>
                            </div>
                            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                                <?php if ($order['status'] === 'dikirim'): ?>
                                    <a href="<?= $base_url ?>/frontend/proses/proses_pesanan.php?action=complete&id=<?= $order['id_order'] ?>"
                                        class="btn btn-success btn-sm font-weight-bold"
                                        onclick="return confirm('Konfirmasi bahwa pesanan #ORD-<?= str_pad($order['id_order'], 5, '0', STR_PAD_LEFT) ?> sudah sampai dan Anda terima dengan baik?')">
                                        <i class="fas fa-check-circle mr-1"></i> Konfirmasi Pesanan Diterima
                                    </a>
                                <?php endif; ?>
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

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

<?php if ($order_success): ?>
    <!-- Modal Pop-up Pesanan Berhasil Dibuat (Pop-up Centered) -->
    <div class="modal fade modal-order-success" id="orderSuccessModal" tabindex="-1" role="dialog" aria-hidden="true"
        data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="order-success-icon-circle">
                        <i class="fas fa-check"></i>
                    </div>

                    <h3 class="font-weight-bold mb-2"
                        style="color: #2c3e50; font-family: var(--font-heading); font-size: 1.5rem;">
                        Pesanan Berhasil Dibuat!
                    </h3>
                    <p class="text-muted mb-4" style="font-size: 0.95rem; line-height: 1.5;">
                        Terima kasih telah berbelanja di <strong>Cipamilk</strong>. Pesanan Anda telah tercatat dan segera
                        kami siapkan.
                    </p>

                    <div class="order-success-summary">
                        <div class="summary-row">
                            <span class="text-muted" style="font-size: 0.88rem;">No. Pesanan:</span>
                            <strong style="color: var(--primary); font-family: var(--font-heading); font-size: 0.98rem;">
                                <?= htmlspecialchars($order_success['order_number']) ?>
                            </strong>
                        </div>
                        <div class="summary-row">
                            <span class="text-muted" style="font-size: 0.88rem;">Total Pembayaran:</span>
                            <strong style="color: #2ECC71; font-size: 1.12rem;">
                                <?= formatRupiah($order_success['total']) ?>
                            </strong>
                        </div>
                        <?php if (!empty($order_success['alamat'])): ?>
                            <div class="summary-row">
                                <span class="text-muted" style="font-size: 0.88rem;">Alamat Pengiriman:</span>
                                <span class="text-truncate text-right"
                                    style="max-width: 220px; font-size: 0.88rem; font-weight: 600;"
                                    title="<?= htmlspecialchars($order_success['alamat']) ?>">
                                    <?= htmlspecialchars($order_success['alamat']) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <div class="summary-row">
                            <span class="text-muted" style="font-size: 0.88rem;">Status:</span>
                            <span class="badge badge-warning text-uppercase"
                                style="border-radius: 20px; padding: 0.35rem 0.75rem; font-weight: 700; font-size: 0.75rem;">
                                Menunggu Konfirmasi
                            </span>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2 mt-4" style="gap: 10px;">
                        <button type="button" class="btn btn-primary-cipamilk flex-fill py-2" data-dismiss="modal">
                            <i class="fas fa-receipt mr-1"></i> Lihat Daftar Pesanan
                        </button>
                        <a href="<?= $base_url ?>/frontend/produk.php" class="btn btn-outline-cipamilk flex-fill py-2">
                            <i class="fas fa-shopping-bag mr-1"></i> Belanja Lagi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof jQuery !== 'undefined' && $('#orderSuccessModal').length) {
                $('#orderSuccessModal').modal('show');
            }
        });
    </script>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>