<?php
/**
 * Admin Produk — Cipamilk E-Commerce
 */
$page_title = 'Kelola Produk — Admin Cipamilk';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

// Ambil kategori untuk form
$categories = $pdo->query("SELECT * FROM categories ORDER BY nama_kategori")->fetchAll();

// Ambil semua produk
$products = $pdo->query("SELECT p.*, c.nama_kategori FROM products p JOIN categories c ON p.id_category = c.id_category ORDER BY p.created_at DESC")->fetchAll();

// Edit mode
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id_product = ?");
    $stmt->execute([(int) $_GET['edit']]);
    $edit = $stmt->fetch();
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight: 800;"><i class="fas fa-box-open mr-2"></i> Kelola Produk</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= $base_url ?>/admin/index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Produk</li>
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

            <div class="row">
                <!-- Form -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff;">
                            <h3 class="card-title" style="font-weight: 700;">
                                <i class="fas fa-<?= $edit ? 'edit' : 'plus-circle' ?> mr-2"></i>
                                <?= $edit ? 'Edit Produk' : 'Tambah Produk' ?>
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="<?= $base_url ?>/admin/proses/proses_produk.php" method="POST"
                                enctype="multipart/form-data">
                                <input type="hidden" name="action" value="<?= $edit ? 'update' : 'create' ?>">
                                <?php if ($edit): ?>
                                    <input type="hidden" name="id_product" value="<?= $edit['id_product'] ?>">
                                    <input type="hidden" name="gambar_lama" value="<?= $edit['gambar'] ?>">
                                <?php endif; ?>

                                <div class="form-group">
                                    <label>Nama Produk</label>
                                    <input type="text" name="nama_produk" class="form-control"
                                        value="<?= htmlspecialchars($edit['nama_produk'] ?? '') ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="id_category" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id_category'] ?>" <?= ($edit && $edit['id_category'] == $cat['id_category']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['nama_kategori']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Harga</label>
                                            <input type="number" name="harga" class="form-control"
                                                value="<?= $edit['harga'] ?? '' ?>" required min="0">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Stok</label>
                                            <input type="number" name="stok" class="form-control"
                                                value="<?= $edit['stok'] ?? '' ?>" required min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Gambar</label>
                                    <input type="file" name="gambar" class="form-control-file" accept="image/*">
                                    <?php if ($edit && $edit['gambar']): ?>
                                        <div class="mt-2 d-flex align-items-center">
                                            <img src="<?= $base_url ?>/assets/images/products/<?= htmlspecialchars($edit['gambar']) ?>"
                                                alt="Preview"
                                                style="width: 45px; height: 45px; object-fit: cover; border-radius: 6px; border: 1px solid #dee2e6; margin-right: 10px;">
                                            <small class="text-muted">File saat ini:
                                                <?= htmlspecialchars($edit['gambar']) ?></small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control"
                                        rows="3"><?= htmlspecialchars($edit['deskripsi'] ?? '') ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-save mr-1"></i> <?= $edit ? 'Update' : 'Simpan' ?>
                                </button>
                                <?php if ($edit): ?>
                                    <a href="<?= $base_url ?>/admin/produk.php"
                                        class="btn btn-secondary btn-block">Batal</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff; padding: 14px 20px;">
                            <h3 class="card-title font-weight-bold m-0" style="font-size: 1.05rem;"><i
                                    class="fas fa-list mr-2"></i> Daftar Produk</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                        <tr>
                                            <th style="width: 50px; text-align: center; vertical-align: middle;">#</th>
                                            <th style="vertical-align: middle;">Produk</th>
                                            <th style="width: 150px; text-align: center; vertical-align: middle;">Gambar
                                            </th>
                                            <th style="vertical-align: middle;">Kategori</th>
                                            <th style="vertical-align: middle;">Harga</th>
                                            <th style="width: 120px; text-align: center; vertical-align: middle;">Stok
                                            </th>
                                            <th style="width: 100px; text-align: center; vertical-align: middle;">Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $i => $p): ?>
                                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                                <td style="text-align: center; vertical-align: middle;"><?= $i + 1 ?></td>
                                                <td style="vertical-align: middle;">
                                                    <strong><?= htmlspecialchars($p['nama_produk']) ?></strong>
                                                </td>
                                                <td style="text-align: center; vertical-align: middle;">
                                                    <?php if (!empty($p['gambar'])): ?>
                                                        <img src="<?= $base_url ?>/assets/images/products/<?= htmlspecialchars($p['gambar']) ?>"
                                                            alt="<?= htmlspecialchars($p['nama_produk']) ?>"
                                                            style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 2px 5px rgba(0,0,0,0.05);"
                                                            onerror="this.parentElement.innerHTML='<div style=\'width:48px;height:48px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#94a3b8;margin:auto;\'><i class=\'fas fa-image\'></i></div>'">
                                                    <?php else: ?>
                                                        <div
                                                            style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #94a3b8; margin: auto;">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <span class="badge badge-info"
                                                        style="border-radius: 20px; padding: 0.35rem 0.7rem; font-weight: 600;">
                                                        <?= htmlspecialchars($p['nama_kategori']) ?>
                                                    </span>
                                                </td>
                                                <td style="vertical-align: middle; font-weight: 700; color: #2c3e50;">
                                                    <?= formatRupiah($p['harga']) ?>
                                                </td>
                                                <td style="text-align: center; vertical-align: middle;">
                                                    <span class="badge badge-<?= $p['stok'] > 0 ? 'success' : 'danger' ?>"
                                                        style="border-radius: 20px; padding: 0.35rem 0.7rem; font-weight: 700;">
                                                        <?= $p['stok'] ?>
                                                    </span>
                                                </td>
                                                <td style="text-align: center; vertical-align: middle;">
                                                    <div class="d-inline-flex" style="gap: 4px;">
                                                        <a href="<?= $base_url ?>/admin/produk.php?edit=<?= $p['id_product'] ?>"
                                                            class="btn btn-sm btn-warning" title="Edit Produk"
                                                            style="border-radius: 6px;">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="<?= $base_url ?>/admin/proses/proses_produk.php?action=delete&id=<?= $p['id_product'] ?>"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Hapus produk ini?')"
                                                            title="Hapus Produk" style="border-radius: 6px;">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($products)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">Belum ada produk</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>