<?php
/**
 * Admin Kategori — Cipamilk E-Commerce
 */
$page_title = 'Kelola Kategori — Admin Cipamilk';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.id_category = c.id_category) as total_produk FROM categories c ORDER BY c.nama_kategori")->fetchAll();

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id_category = ?");
    $stmt->execute([(int) $_GET['edit']]);
    $edit = $stmt->fetch();
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight: 800;"><i class="fas fa-tags mr-2"></i> Kelola Kategori</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $base_url ?>/admin/index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kategori</li>
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
                    <i class="fas fa-check-circle mr-1"></i> <?= $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible" style="border-radius: 8px;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-exclamation-circle mr-1"></i> <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff;">
                            <h3 class="card-title" style="font-weight: 700;">
                                <i class="fas fa-<?= $edit ? 'edit' : 'plus-circle' ?> mr-2"></i>
                                <?= $edit ? 'Edit Kategori' : 'Tambah Kategori' ?>
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="<?= $base_url ?>/admin/proses/proses_kategori.php" method="POST">
                                <input type="hidden" name="action" value="<?= $edit ? 'update' : 'create' ?>">
                                <?php if ($edit): ?>
                                    <input type="hidden" name="id_category" value="<?= $edit['id_category'] ?>">
                                <?php endif; ?>
                                <div class="form-group">
                                    <label>Nama Kategori</label>
                                    <input type="text" name="nama_kategori" class="form-control"
                                        value="<?= htmlspecialchars($edit['nama_kategori'] ?? '') ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-save mr-1"></i> <?= $edit ? 'Update' : 'Simpan' ?>
                                </button>
                                <?php if ($edit): ?>
                                    <a href="<?= $base_url ?>/admin/kategori.php"
                                        class="btn btn-secondary btn-block">Batal</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff;">
                            <h3 class="card-title" style="font-weight: 700;"><i class="fas fa-list mr-2"></i> Daftar
                                Kategori</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Kategori</th>
                                        <th>Jumlah Produk</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $i => $cat): ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><strong><?= htmlspecialchars($cat['nama_kategori']) ?></strong></td>
                                            <td><span class="badge badge-info"
                                                    style="border-radius: 20px; padding: 0.3rem 0.6rem;"><?= $cat['total_produk'] ?>
                                                    produk</span></td>
                                            <td>
                                                <a href="<?= $base_url ?>/admin/kategori.php?edit=<?= $cat['id_category'] ?>"
                                                    class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                                <a href="<?= $base_url ?>/admin/proses/proses_kategori.php?action=delete&id=<?= $cat['id_category'] ?>"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Hapus kategori ini? Semua produk di dalamnya juga akan terhapus.')"><i
                                                        class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($categories)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">Belum ada kategori</td>
                                        </tr>
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