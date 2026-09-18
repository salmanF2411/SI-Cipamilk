<?php
/**
 * Login — Cipamilk E-Commerce (Satu Pintu: Customer & Admin)
 */
$page_title = 'Login — Cipamilk';
require_once __DIR__ . '/../config/database.php';

// Redirect jika sudah login
if (isLoggedIn()) {
    if (isAdmin()) {
        redirect($base_url . '/admin/index.php');
    } else {
        redirect($base_url . '/frontend/index.php');
    }
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
    <div class="auth-card">
        <div class="auth-logo">
            <h2><i class="fas fa-cow" style="color: var(--accent);"></i> Cipamilk</h2>
            <p>Masuk ke akun Anda</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-cipamilk alert-danger-cipamilk mb-3">
                <i class="fas fa-exclamation-circle"></i>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert-cipamilk alert-success-cipamilk mb-3">
                <i class="fas fa-check-circle"></i>
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= $base_url ?>/frontend/proses/proses_login.php" method="POST">
            <div class="form-group">
                <label class="form-label-cipamilk">Email</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="background: #FAFAFA; border: 2px solid var(--border); border-right: none; border-radius: var(--radius-sm) 0 0 var(--radius-sm);">
                            <i class="fas fa-envelope" style="color: var(--primary);"></i>
                        </span>
                    </div>
                    <input type="email" name="email" class="form-control form-control-cipamilk" placeholder="contoh@email.com" required style="border-left: none; border-radius: 0 var(--radius-sm) var(--radius-sm) 0;">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label-cipamilk">Password</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="background: #FAFAFA; border: 2px solid var(--border); border-right: none; border-radius: var(--radius-sm) 0 0 var(--radius-sm);">
                            <i class="fas fa-lock" style="color: var(--primary);"></i>
                        </span>
                    </div>
                    <input type="password" name="password" class="form-control form-control-cipamilk" placeholder="Masukkan password" required style="border-left: none; border-radius: 0 var(--radius-sm) var(--radius-sm) 0;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary-cipamilk btn-block mt-4">
                <i class="fas fa-sign-in-alt mr-2"></i> Masuk
            </button>
        </form>

        <div class="text-center mt-4">
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Belum punya akun?
                <a href="<?= $base_url ?>/frontend/register.php" style="font-weight: 700; color: var(--primary);">Daftar Sekarang</a>
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
