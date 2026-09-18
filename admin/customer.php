<?php
/**
 * Admin Customer — Cipamilk E-Commerce
 */
$page_title = 'Kelola Customer — Admin Cipamilk';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$customers = $pdo->query("
    SELECT c.*, u.nama, u.email, u.created_at,
    (SELECT COUNT(*) FROM orders o WHERE o.id_customer = c.id_customer) as total_order,
    (SELECT COUNT(*) FROM subscriptions s WHERE s.id_customer = c.id_customer AND s.status = 'aktif') as total_subscription
    FROM customers c
    JOIN users u ON c.id_user = u.id_user
    ORDER BY u.created_at DESC
")->fetchAll();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight: 800;"><i class="fas fa-users mr-2"></i> Kelola Customer</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $base_url ?>/admin/index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Customer</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff;">
                    <h3 class="card-title" style="font-weight: 700;"><i class="fas fa-list mr-2"></i> Daftar Customer</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Pesanan</th>
                                <th>Subscription</th>
                                <th>Tgl Daftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $i => $cust): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><strong><?= htmlspecialchars($cust['nama']) ?></strong></td>
                                <td><?= htmlspecialchars($cust['email']) ?></td>
                                <td><?= htmlspecialchars($cust['nomor_telepon'] ?? '-') ?></td>
                                <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($cust['alamat'] ?? '-') ?>"><?= htmlspecialchars($cust['alamat'] ?? '-') ?></td>
                                <td><span class="badge badge-info" style="border-radius: 20px; padding: 0.3rem 0.6rem;"><?= $cust['total_order'] ?></span></td>
                                <td><span class="badge badge-aktif" style="border-radius: 20px; padding: 0.3rem 0.6rem;"><?= $cust['total_subscription'] ?></span></td>
                                <td><?= date('d M Y', strtotime($cust['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($customers)): ?>
                            <tr><td colspan="8" class="text-center text-muted py-4">Belum ada customer</td></tr>
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
