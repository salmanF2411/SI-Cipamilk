<?php
/**
 * Admin Subscription — Cipamilk E-Commerce
 */
$page_title = 'Kelola Subscription — Admin Cipamilk';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$subscriptions = $pdo->query("
    SELECT s.*, u.nama, p.nama_produk, p.harga
    FROM subscriptions s
    JOIN customers c ON s.id_customer = c.id_customer
    JOIN users u ON c.id_user = u.id_user
    JOIN products p ON s.id_product = p.id_product
    ORDER BY s.created_at DESC
")->fetchAll();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight: 800;"><i class="fas fa-calendar-check mr-2"></i> Kelola Subscription</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $base_url ?>/admin/index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Subscription</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible" style="border-radius: 8px;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-check-circle mr-1"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff;">
                    <h3 class="card-title" style="font-weight: 700;"><i class="fas fa-list mr-2"></i> Semua Subscription</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Produk</th>
                                <th>Periode</th>
                                <th>Tgl Mulai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subscriptions as $sub): ?>
                            <tr>
                                <td><strong>#SUB-<?= str_pad($sub['id_subscription'], 4, '0', STR_PAD_LEFT) ?></strong></td>
                                <td><?= htmlspecialchars($sub['nama']) ?></td>
                                <td><?= htmlspecialchars($sub['nama_produk']) ?></td>
                                <td><span class="badge badge-info" style="border-radius: 20px; padding: 0.3rem 0.6rem;"><?= ucfirst($sub['periode']) ?></span></td>
                                <td><?= date('d M Y', strtotime($sub['tanggal_mulai'])) ?></td>
                                <td>
                                    <span class="badge badge-<?= $sub['status'] ?>" style="padding: 0.3rem 0.6rem; border-radius: 20px; font-size: 0.75rem;">
                                        <?= ucfirst($sub['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="<?= $base_url ?>/admin/proses/proses_subscription.php?action=update&id=<?= $sub['id_subscription'] ?>&status=aktif">Aktif</a>
                                            <a class="dropdown-item" href="<?= $base_url ?>/admin/proses/proses_subscription.php?action=update&id=<?= $sub['id_subscription'] ?>&status=selesai">Selesai</a>
                                            <a class="dropdown-item text-danger" href="<?= $base_url ?>/admin/proses/proses_subscription.php?action=update&id=<?= $sub['id_subscription'] ?>&status=dibatalkan">Dibatalkan</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($subscriptions)): ?>
                            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada subscription</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
