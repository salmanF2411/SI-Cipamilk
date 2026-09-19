<?php
/**
 * Admin Pesanan — Cipamilk E-Commerce
 */
$page_title = 'Kelola Pesanan — Admin Cipamilk';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

// Filter Status
$filter_status = sanitize($_GET['status'] ?? '');
$valid_statuses = ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'];

// Hitung total per status
$status_counts = [];
$counts_query = $pdo->query("SELECT status, COUNT(*) as total FROM orders GROUP BY status")->fetchAll();
$total_all = 0;
foreach ($counts_query as $cq) {
    $status_counts[$cq['status']] = (int) $cq['total'];
    $total_all += (int) $cq['total'];
}

// Query pesanan
if (in_array($filter_status, $valid_statuses)) {
    $stmt = $pdo->prepare("
        SELECT o.*, u.nama, u.email, c.alamat AS customer_alamat, c.nomor_telepon AS customer_telepon
        FROM orders o
        JOIN customers c ON o.id_customer = c.id_customer
        JOIN users u ON c.id_user = u.id_user
        WHERE o.status = ?
        ORDER BY o.tanggal DESC
    ");
    $stmt->execute([$filter_status]);
    $orders = $stmt->fetchAll();
} else {
    $filter_status = '';
    $orders = $pdo->query("
        SELECT o.*, u.nama, u.email, c.alamat AS customer_alamat, c.nomor_telepon AS customer_telepon
        FROM orders o
        JOIN customers c ON o.id_customer = c.id_customer
        JOIN users u ON c.id_user = u.id_user
        ORDER BY o.tanggal DESC
    ")->fetchAll();
}

// Helper badge status
function renderStatusBadge($status)
{
    switch ($status) {
        case 'pending':
            return '<span class="badge" style="background: rgba(244,201,93,0.22); color: #b8860b; border: 1px solid rgba(244,201,93,0.4); padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.8rem;"><i class="fas fa-clock mr-1"></i> Pending</span>';
        case 'diproses':
            return '<span class="badge" style="background: rgba(77,168,218,0.2); color: #2471a3; border: 1px solid rgba(77,168,218,0.35); padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.8rem;"><i class="fas fa-sync-alt fa-spin mr-1"></i> Diproses</span>';
        case 'dikirim':
            return '<span class="badge" style="background: rgba(52,152,219,0.2); color: #1a5276; border: 1px solid rgba(52,152,219,0.35); padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.8rem;"><i class="fas fa-truck mr-1"></i> Dikirim</span>';
        case 'selesai':
            return '<span class="badge" style="background: rgba(79,119,45,0.2); color: #2e5318; border: 1px solid rgba(79,119,45,0.35); padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.8rem;"><i class="fas fa-check-circle mr-1"></i> Selesai</span>';
        case 'dibatalkan':
            return '<span class="badge" style="background: rgba(231,76,60,0.18); color: #c0392b; border: 1px solid rgba(231,76,60,0.3); padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.8rem;"><i class="fas fa-times-circle mr-1"></i> Dibatalkan</span>';
        default:
            return '<span class="badge badge-secondary" style="padding: 6px 12px; border-radius: 20px;">' . htmlspecialchars(ucfirst($status)) . '</span>';
    }
}
?>

<div class="content-wrapper">
    <!-- Page Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight: 800; color: #2c3e50;">
                        <i class="fas fa-shopping-bag mr-2" style="color: var(--primary);"></i> Kelola Pesanan
                    </h1>
                    <p class="text-muted mb-0 mt-1" style="font-size: 0.9rem;">
                        Pantau riwayat transaksi, status pengiriman, dan rincian pesanan pelanggan.
                    </p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right" style="background: transparent;">
                        <li class="breadcrumb-item"><a href="<?= $base_url ?>/admin/index.php"
                                style="color: var(--primary);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kelola Pesanan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Flash Notification -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert"
                    style="border-radius: 10px; border-left: 5px solid #28a745;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle mr-2" style="font-size: 1.2rem;"></i>
                        <span><?= $_SESSION['success'];
                        unset($_SESSION['success']); ?></span>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert"
                    style="border-radius: 10px; border-left: 5px solid #dc3545;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle mr-2" style="font-size: 1.2rem;"></i>
                        <span><?= $_SESSION['error'];
                        unset($_SESSION['error']); ?></span>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Status Filter Tabs -->
            <div class="card mb-3 shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body p-2">
                    <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
                        <span class="mr-2 font-weight-bold text-muted ml-2" style="font-size: 0.85rem;">
                            <i class="fas fa-filter mr-1"></i> Filter:
                        </span>
                        <a href="<?= $base_url ?>/admin/pesanan.php"
                            class="btn btn-sm <?= empty($filter_status) ? 'btn-primary' : 'btn-light' ?>"
                            style="border-radius: 20px; font-weight: 600; padding: 5px 14px;">
                            Semua <span class="badge badge-light ml-1"><?= $total_all ?></span>
                        </a>
                        <a href="<?= $base_url ?>/admin/pesanan.php?status=pending"
                            class="btn btn-sm <?= $filter_status === 'pending' ? 'btn-warning text-dark' : 'btn-light' ?>"
                            style="border-radius: 20px; font-weight: 600; padding: 5px 14px;">
                            Pending <span
                                class="badge <?= $filter_status === 'pending' ? 'badge-dark' : 'badge-warning' ?> ml-1"><?= $status_counts['pending'] ?? 0 ?></span>
                        </a>
                        <a href="<?= $base_url ?>/admin/pesanan.php?status=diproses"
                            class="btn btn-sm <?= $filter_status === 'diproses' ? 'btn-info' : 'btn-light' ?>"
                            style="border-radius: 20px; font-weight: 600; padding: 5px 14px;">
                            Diproses <span class="badge badge-info ml-1"><?= $status_counts['diproses'] ?? 0 ?></span>
                        </a>
                        <a href="<?= $base_url ?>/admin/pesanan.php?status=dikirim"
                            class="btn btn-sm <?= $filter_status === 'dikirim' ? 'btn-primary' : 'btn-light' ?>"
                            style="border-radius: 20px; font-weight: 600; padding: 5px 14px;">
                            Dikirim <span class="badge badge-primary ml-1"><?= $status_counts['dikirim'] ?? 0 ?></span>
                        </a>
                        <a href="<?= $base_url ?>/admin/pesanan.php?status=selesai"
                            class="btn btn-sm <?= $filter_status === 'selesai' ? 'btn-success' : 'btn-light' ?>"
                            style="border-radius: 20px; font-weight: 600; padding: 5px 14px;">
                            Selesai <span class="badge badge-success ml-1"><?= $status_counts['selesai'] ?? 0 ?></span>
                        </a>
                        <a href="<?= $base_url ?>/admin/pesanan.php?status=dibatalkan"
                            class="btn btn-sm <?= $filter_status === 'dibatalkan' ? 'btn-danger' : 'btn-light' ?>"
                            style="border-radius: 20px; font-weight: 600; padding: 5px 14px;">
                            Dibatalkan <span
                                class="badge badge-danger ml-1"><?= $status_counts['dibatalkan'] ?? 0 ?></span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Orders Table Card -->
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header"
                    style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff; padding: 14px 20px;">
                    <div class="d-flex align-items-center">
                        <h3 class="card-title font-weight-bold m-0"
                            style="font-size: 1.05rem; display: flex; align-items: center;">
                            <i class="fas fa-list mr-2"></i> Daftar Pesanan
                        </h3>
                        <span class="badge badge-light ml-3 font-weight-bold"
                            style="color: var(--primary); border-radius: 20px; font-size: 0.85rem; padding: 5px 14px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <?= count($orders) ?> Pesanan
                        </span>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="width: 100%;">
                            <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                <tr>
                                    <th
                                        style="padding: 14px 16px; font-weight: 700; font-size: 0.85rem; color: #475569; width: 130px;">
                                        No. Pesanan</th>
                                    <th
                                        style="padding: 14px 16px; font-weight: 700; font-size: 0.85rem; color: #475569; width: 170px;">
                                        Customer</th>
                                    <th
                                        style="padding: 14px 16px; font-weight: 700; font-size: 0.85rem; color: #475569; min-width: 220px;">
                                        Alamat Pengiriman</th>
                                    <th
                                        style="padding: 14px 16px; font-weight: 700; font-size: 0.85rem; color: #475569; width: 150px;">
                                        Tanggal Pesan</th>
                                    <th
                                        style="padding: 14px 16px; font-weight: 700; font-size: 0.85rem; color: #475569; width: 140px;">
                                        Total Bayar</th>
                                    <th
                                        style="padding: 14px 16px; font-weight: 700; font-size: 0.85rem; color: #475569; width: 130px;">
                                        Status</th>
                                    <th
                                        style="padding: 14px 16px; font-weight: 700; font-size: 0.85rem; color: #475569; width: 160px; text-align: center;">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orders)): ?>
                                    <?php foreach ($orders as $order): ?>
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <!-- No. Pesanan -->
                                            <td style="padding: 14px 16px; vertical-align: middle;">
                                                <span
                                                    style="font-weight: 800; color: #1e293b; font-family: monospace; font-size: 0.95rem;">
                                                    #ORD-<?= str_pad($order['id_order'], 5, '0', STR_PAD_LEFT) ?>
                                                </span>
                                            </td>

                                            <!-- Customer Info -->
                                            <td style="padding: 14px 16px; vertical-align: middle;">
                                                <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem;">
                                                    <?= htmlspecialchars($order['nama']) ?>
                                                </div>
                                                <div class="text-muted" style="font-size: 0.8rem; margin-top: 2px;">
                                                    <i class="fas fa-phone-alt text-success mr-1"
                                                        style="font-size: 0.75rem;"></i>
                                                    <?= htmlspecialchars($order['nomor_telepon'] ?? $order['customer_telepon'] ?? '-') ?>
                                                </div>
                                            </td>

                                            <!-- Alamat Pengiriman -->
                                            <td style="padding: 14px 16px; vertical-align: middle;">
                                                <?php
                                                $alamat = !empty($order['alamat_pengiriman']) ? $order['alamat_pengiriman'] : ($order['customer_alamat'] ?? '-');
                                                ?>
                                                <div style="color: #334155; font-size: 0.88rem; line-height: 1.45;">
                                                    <i class="fas fa-map-marker-alt text-danger mr-1"
                                                        style="font-size: 0.8rem;"></i>
                                                    <?= htmlspecialchars($alamat) ?>
                                                </div>
                                            </td>

                                            <!-- Tanggal -->
                                            <td style="padding: 14px 16px; vertical-align: middle;">
                                                <div style="font-weight: 600; color: #334155; font-size: 0.88rem;">
                                                    <?= date('d M Y', strtotime($order['tanggal'])) ?>
                                                </div>
                                                <div class="text-muted" style="font-size: 0.78rem;">
                                                    <i class="far fa-clock mr-1"></i>
                                                    <?= date('H:i', strtotime($order['tanggal'])) ?> WIB
                                                </div>
                                            </td>

                                            <!-- Total -->
                                            <td style="padding: 14px 16px; vertical-align: middle;">
                                                <span style="font-weight: 800; color: var(--green); font-size: 0.95rem;">
                                                    <?= formatRupiah($order['total']) ?>
                                                </span>
                                            </td>

                                            <!-- Status Badge -->
                                            <td style="padding: 14px 16px; vertical-align: middle;">
                                                <?= renderStatusBadge($order['status']) ?>
                                            </td>

                                            <!-- Action Buttons -->
                                            <td style="padding: 14px 16px; vertical-align: middle; text-align: center;">
                                                <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                                    <!-- Detail Modal Button -->
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        data-toggle="modal" data-target="#detailModal<?= $order['id_order'] ?>"
                                                        title="Lihat Detail Pesanan"
                                                        style="border-radius: 6px; font-weight: 600; font-size: 0.8rem; padding: 4px 10px;">
                                                        <i class="fas fa-eye mr-1"></i> Detail
                                                    </button>

                                                    <!-- Status Update Dropdown -->
                                                    <div class="btn-group">
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                            title="Ubah Status"
                                                            style="border-radius: 6px; font-weight: 600; font-size: 0.8rem; padding: 4px 10px;">
                                                            <i class="fas fa-edit mr-1"></i> Status
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right shadow-sm"
                                                            style="border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.85rem;">
                                                            <div class="dropdown-header font-weight-bold"
                                                                style="font-size: 0.72rem; text-transform: uppercase; color: #94a3b8;">
                                                                Pilih Status Baru:
                                                            </div>
                                                            <a class="dropdown-item py-2 <?= $order['status'] === 'pending' ? 'font-weight-bold bg-light' : '' ?>"
                                                                href="<?= $base_url ?>/admin/proses/proses_pesanan.php?action=update&id=<?= $order['id_order'] ?>&status=pending">
                                                                <i class="fas fa-clock text-warning mr-2"></i> Pending
                                                            </a>
                                                            <a class="dropdown-item py-2 <?= $order['status'] === 'diproses' ? 'font-weight-bold bg-light' : '' ?>"
                                                                href="<?= $base_url ?>/admin/proses/proses_pesanan.php?action=update&id=<?= $order['id_order'] ?>&status=diproses">
                                                                <i class="fas fa-sync-alt text-info mr-2"></i> Diproses
                                                            </a>
                                                            <a class="dropdown-item py-2 <?= $order['status'] === 'dikirim' ? 'font-weight-bold bg-light' : '' ?>"
                                                                href="<?= $base_url ?>/admin/proses/proses_pesanan.php?action=update&id=<?= $order['id_order'] ?>&status=dikirim">
                                                                <i class="fas fa-truck text-primary mr-2"></i> Dikirim
                                                            </a>
                                                            <a class="dropdown-item py-2 <?= $order['status'] === 'selesai' ? 'font-weight-bold bg-light' : '' ?>"
                                                                href="<?= $base_url ?>/admin/proses/proses_pesanan.php?action=update&id=<?= $order['id_order'] ?>&status=selesai">
                                                                <i class="fas fa-check-circle text-success mr-2"></i> Selesai
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item py-2 text-danger <?= $order['status'] === 'dibatalkan' ? 'font-weight-bold bg-light' : '' ?>"
                                                                href="<?= $base_url ?>/admin/proses/proses_pesanan.php?action=update&id=<?= $order['id_order'] ?>&status=dibatalkan">
                                                                <i class="fas fa-times-circle mr-2"></i> Dibatalkan
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-box-open fa-3x mb-3 text-secondary"
                                                    style="opacity: 0.4;"></i>
                                                <h5 style="font-weight: 600;">Tidak Ada Pesanan Ditemukan</h5>
                                                <p class="mb-0" style="font-size: 0.9rem;">
                                                    <?= !empty($filter_status) ? 'Tidak ada pesanan dengan status ' . ucfirst($filter_status) . '.' : 'Belum ada transaksi pesanan yang tercatat.' ?>
                                                </p>
                                                <?php if (!empty($filter_status)): ?>
                                                    <a href="<?= $base_url ?>/admin/pesanan.php"
                                                        class="btn btn-sm btn-primary mt-3" style="border-radius: 20px;">
                                                        Lihat Semua Pesanan
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- ============================================ -->
<!-- MODALS DITARUH DI LUAR TABLE (AGAR TIDAK MERUSAK DOM) -->
<!-- ============================================ -->
<?php if (!empty($orders)): ?>
    <?php foreach ($orders as $order): ?>
        <div class="modal fade" id="detailModal<?= $order['id_order'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content"
                    style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                    <!-- Modal Header -->
                    <div class="modal-header"
                        style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff; padding: 16px 24px;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-receipt mr-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h5 class="modal-title font-weight-bold mb-0" style="font-size: 1.15rem;">
                                    Rincian Pesanan #ORD-<?= str_pad($order['id_order'], 5, '0', STR_PAD_LEFT) ?>
                                </h5>
                                <small style="opacity: 0.85;">Dipesan pada:
                                    <?= date('d F Y, H:i', strtotime($order['tanggal'])) ?> WIB</small>
                            </div>
                        </div>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                            style="opacity: 0.9; text-shadow: none;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body" style="padding: 24px;">
                        <!-- Informasi Customer & Pengiriman -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="p-3"
                                    style="background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; height: 100%;">
                                    <h6 class="font-weight-bold text-muted mb-3"
                                        style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <i class="fas fa-user mr-1 text-primary"></i> Data Pelanggan
                                    </h6>
                                    <div class="mb-2">
                                        <strong style="color: #1e293b;"><?= htmlspecialchars($order['nama']) ?></strong>
                                    </div>
                                    <div class="text-muted mb-1" style="font-size: 0.9rem;">
                                        <i class="fas fa-envelope mr-2 text-secondary"></i>
                                        <?= htmlspecialchars($order['email'] ?? '-') ?>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.9rem;">
                                        <i class="fas fa-phone mr-2 text-success"></i>
                                        <?= htmlspecialchars($order['nomor_telepon'] ?? '-') ?>
                                        <?php
                                        $wa_number = $order['nomor_telepon'] ?? '';
                                        if (!empty($wa_number)) {
                                            // Konversi 08xx ke 628xx untuk format WhatsApp
                                            $wa_number = preg_replace('/^0/', '62', $wa_number);
                                            $wa_number = preg_replace('/[^0-9]/', '', $wa_number);
                                        ?>
                                            <a href="https://wa.me/<?= $wa_number ?>" target="_blank" title="Hubungi via WhatsApp"
                                                style="color: #25D366; margin-left: 6px; font-size: 1.1rem; transition: opacity 0.2s;"
                                                onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3"
                                    style="background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; height: 100%;">
                                    <h6 class="font-weight-bold text-muted mb-3"
                                        style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <i class="fas fa-map-marker-alt mr-1 text-danger"></i> Alamat Pengiriman
                                    </h6>
                                    <p class="mb-2" style="color: #334155; font-size: 0.9rem; line-height: 1.5;">
                                        <?= nl2br(htmlspecialchars($order['alamat_pengiriman'] ?? '-')) ?>
                                    </p>
                                    <div class="mt-2">
                                        <span class="text-muted" style="font-size: 0.85rem;">Status Saat Ini:</span>
                                        <div class="mt-1"><?= renderStatusBadge($order['status']) ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="mb-4">
                            <div class="p-3" style="background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0;">
                                <h6 class="font-weight-bold text-muted mb-3"
                                    style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fas fa-money-bill-wave mr-1" style="color: var(--primary);"></i> Metode Pembayaran
                                </h6>
                                <?php if (($order['metode_pembayaran'] ?? 'cod') === 'qris'): ?>
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge"
                                            style="background: rgba(142,68,173,0.15); color: #8e44ad; border: 1px solid rgba(142,68,173,0.3); padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 0.85rem;">
                                            <i class="fas fa-qrcode mr-1"></i> QRIS
                                        </span>
                                    </div>
                                    <div style="font-size: 0.85rem; color: #6b7280;">
                                        <i class="fas fa-info-circle mr-1" style="color: #8e44ad;"></i>
                                        Pembayaran via QRIS — Tunjukan bukti QRIS saat menerima pesanan
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge"
                                            style="background: rgba(39,174,96,0.15); color: #27ae60; border: 1px solid rgba(39,174,96,0.3); padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 0.85rem;">
                                            <i class="fas fa-hand-holding-usd mr-1"></i> Bayar di Tempat (COD)
                                        </span>
                                    </div>
                                    <div style="font-size: 0.85rem; color: #6b7280;">
                                        <i class="fas fa-info-circle mr-1" style="color: #f39c12;"></i>
                                        Pembayaran tunai saat pesanan diterima
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Item Pesanan Table -->
                        <h6 class="font-weight-bold text-muted mb-2"
                            style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="fas fa-shopping-basket mr-1 text-primary"></i> Daftar Produk yang Dipesan
                        </h6>
                        <div class="table-responsive" style="border-radius: 8px; border: 1px solid #e2e8f0;">
                            <table class="table table-sm mb-0">
                                <thead style="background: #f1f5f9;">
                                    <tr>
                                        <th style="padding: 10px 14px; font-weight: 700; color: #475569; width: 45%;">Produk
                                        </th>
                                        <th
                                            style="padding: 10px 14px; font-weight: 700; color: #475569; text-align: center; width: 15%;">
                                            Jumlah</th>
                                        <th
                                            style="padding: 10px 14px; font-weight: 700; color: #475569; text-align: right; width: 20%;">
                                            Harga Satuan</th>
                                        <th
                                            style="padding: 10px 14px; font-weight: 700; color: #475569; text-align: right; width: 20%;">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt_det = $pdo->prepare("
                                    SELECT od.*, p.nama_produk, p.gambar, p.harga
                                    FROM order_details od
                                    JOIN products p ON od.id_product = p.id_product
                                    WHERE od.id_order = ?
                                ");
                                    $stmt_det->execute([$order['id_order']]);
                                    $items = $stmt_det->fetchAll();
                                    foreach ($items as $item):
                                        ?>
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <td style="padding: 10px 14px; vertical-align: middle;">
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($item['gambar'])): ?>
                                                        <img src="<?= $base_url ?>/assets/images/products/<?= htmlspecialchars($item['gambar']) ?>"
                                                            alt="<?= htmlspecialchars($item['nama_produk']) ?>"
                                                            style="width: 38px; height: 38px; object-fit: cover; border-radius: 6px; margin-right: 10px; border: 1px solid #e2e8f0;"
                                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                                        <i class="fas fa-box text-primary mr-2" style="opacity: 0.7; display: none;"></i>
                                                    <?php else: ?>
                                                        <i class="fas fa-box text-primary mr-2" style="opacity: 0.7;"></i>
                                                    <?php endif; ?>
                                                    <span
                                                        style="font-weight: 600; color: #1e293b;"><?= htmlspecialchars($item['nama_produk']) ?></span>
                                                </div>
                                            </td>
                                            <td
                                                style="padding: 10px 14px; vertical-align: middle; text-align: center; font-weight: 700;">
                                                <?= $item['jumlah'] ?>x
                                            </td>
                                            <td
                                                style="padding: 10px 14px; vertical-align: middle; text-align: right; color: #475569;">
                                                <?= formatRupiah($item['subtotal'] / max(1, $item['jumlah'])) ?>
                                            </td>
                                            <td
                                                style="padding: 10px 14px; vertical-align: middle; text-align: right; font-weight: 700; color: #1e293b;">
                                                <?= formatRupiah($item['subtotal']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot style="background: #f8fafc; border-top: 2px solid #e2e8f0;">
                                    <tr>
                                        <td colspan="3"
                                            style="padding: 12px 14px; text-align: right; font-weight: 700; color: #1e293b; font-size: 1rem;">
                                            Total Pembayaran:
                                        </td>
                                        <td
                                            style="padding: 12px 14px; text-align: right; font-weight: 800; color: var(--green); font-size: 1.15rem;">
                                            <?= formatRupiah($order['total']) ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer d-flex justify-content-between"
                        style="background: #f8fafc; padding: 14px 24px; border-top: 1px solid #e2e8f0;">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                data-toggle="dropdown" style="border-radius: 6px; font-weight: 600;">
                                <i class="fas fa-exchange-alt mr-1"></i> Ubah Status Pesanan Ini
                            </button>
                            <div class="dropdown-menu shadow-sm" style="border-radius: 8px;">
                                <a class="dropdown-item py-2"
                                    href="<?= $base_url ?>/admin/proses/proses_pesanan.php?action=update&id=<?= $order['id_order'] ?>&status=pending">
                                    <i class="fas fa-clock text-warning mr-2"></i> Pending
                                </a>
                                <a class="dropdown-item py-2"
                                    href="<?= $base_url ?>/admin/proses/proses_pesanan.php?action=update&id=<?= $order['id_order'] ?>&status=diproses">
                                    <i class="fas fa-sync-alt text-info mr-2"></i> Diproses
                                </a>
                                <a class="dropdown-item py-2"
                                    href="<?= $base_url ?>/admin/proses/proses_pesanan.php?action=update&id=<?= $order['id_order'] ?>&status=dikirim">
                                    <i class="fas fa-truck text-primary mr-2"></i> Dikirim
                                </a>
                                <a class="dropdown-item py-2"
                                    href="<?= $base_url ?>/admin/proses/proses_pesanan.php?action=update&id=<?= $order['id_order'] ?>&status=selesai">
                                    <i class="fas fa-check-circle text-success mr-2"></i> Selesai
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item py-2 text-danger"
                                    href="<?= $base_url ?>/admin/proses/proses_pesanan.php?action=update&id=<?= $order['id_order'] ?>&status=dibatalkan">
                                    <i class="fas fa-times-circle mr-2"></i> Dibatalkan
                                </a>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary px-4 font-weight-bold" data-dismiss="modal"
                            style="border-radius: 6px;">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>