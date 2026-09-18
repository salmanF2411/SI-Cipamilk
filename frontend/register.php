<?php
/**
 * Register Customer — Cipamilk E-Commerce
 */
$page_title = 'Daftar — Cipamilk';
require_once __DIR__ . '/../config/database.php';

if (isLoggedIn()) {
    redirect($base_url . '/frontend/index.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/style.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card" style="max-width: 520px;">
        <div class="auth-logo">
            <h2><i class="fas fa-cow" style="color: var(--accent);"></i> Cipamilk</h2>
            <p>Buat akun baru untuk mulai berbelanja</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-cipamilk alert-danger-cipamilk mb-3">
                <i class="fas fa-exclamation-circle"></i>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= $base_url ?>/frontend/proses/proses_register.php" method="POST">
            <div class="form-group">
                <label class="form-label-cipamilk">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control form-control-cipamilk" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="form-group">
                <label class="form-label-cipamilk">Email</label>
                <input type="email" name="email" class="form-control form-control-cipamilk" placeholder="contoh@email.com" required>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label-cipamilk">Password</label>
                        <input type="password" name="password" class="form-control form-control-cipamilk" placeholder="Min. 6 karakter" required minlength="6">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label-cipamilk">Konfirmasi Password</label>
                        <input type="password" name="konfirmasi_password" class="form-control form-control-cipamilk" placeholder="Ulangi password" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label-cipamilk">Nomor Telepon</label>
                <input type="text" name="nomor_telepon" class="form-control form-control-cipamilk" placeholder="08xxxxxxxxxx" required>
            </div>

            <div class="form-group">
                <label class="form-label-cipamilk">Alamat</label>
                <textarea name="alamat" class="form-control form-control-cipamilk" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary-cipamilk btn-block mt-3">
                <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
            </button>
        </form>

        <div class="text-center mt-4">
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Sudah punya akun?
                <a href="<?= $base_url ?>/frontend/login.php" style="font-weight: 700; color: var(--primary);">Masuk</a>
            </p>
            <a href="<?= $base_url ?>/frontend/index.php" style="font-size: 0.85rem; color: var(--text-muted);">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<script src="<?= $base_url ?>/assets/js/script.js"></script>
</body>
</html>
