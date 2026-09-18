<?php
/**
 * Header & Navbar — Cipamilk E-Commerce
 * Reusable include untuk semua halaman frontend
 */

if (!isset($base_url)) {
    require_once __DIR__ . '/../config/database.php';
}

// Hitung jumlah item keranjang
$cart_count = 0;
if (isLoggedIn() && $_SESSION['role'] === 'customer') {
    $stmt_cart = $pdo->prepare("SELECT SUM(jumlah) as total FROM cart WHERE id_customer = ?");
    $stmt_cart->execute([$_SESSION['id_customer'] ?? 0]);
    $cart_count = $stmt_cart->fetch()['total'] ?? 0;
}

// Halaman aktif
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cipamilk — E-Commerce Olahan Susu Segar. Pesan susu segar, yogurt, keju, dan es krim langsung dari peternakan.">
    <title><?= $page_title ?? 'Cipamilk — Olahan Susu Segar' ?></title>

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-cipamilk">
    <div class="container">
        <a class="navbar-brand" href="<?= $base_url ?>/frontend/index.php">
            <i class="fas fa-cow"></i> Cipamilk
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCipamilk">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCipamilk">
            <ul class="navbar-nav ml-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'index' ? 'active' : '' ?>" href="<?= $base_url ?>/frontend/index.php">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'produk' ? 'active' : '' ?>" href="<?= $base_url ?>/frontend/produk.php">
                        <i class="fas fa-box-open mr-1"></i> Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'subscription' ? 'active' : '' ?>" href="<?= $base_url ?>/frontend/subscription.php">
                        <i class="fas fa-calendar-check mr-1"></i> Subscription
                    </a>
                </li>

                <?php if (isLoggedIn() && $_SESSION['role'] === 'customer'): ?>
                    <li class="nav-item">
                        <a class="nav-link btn-nav-cart <?= $current_page === 'keranjang' ? 'active' : '' ?>" href="<?= $base_url ?>/frontend/keranjang.php" id="nav-cart-btn" title="Keranjang Belanja">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge" id="nav-cart-badge" style="<?= $cart_count > 0 ? '' : 'display: none;' ?>"><?= $cart_count ?></span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            <i class="fas fa-user-circle mr-1"></i> <?= htmlspecialchars($_SESSION['nama']) ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <?php if (isAdmin()): ?>
                                <a class="dropdown-item" href="<?= $base_url ?>/admin/index.php" style="color: var(--primary); font-weight: 600;">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Panel Admin
                                </a>
                                <div class="dropdown-divider"></div>
                            <?php else: ?>
                                <a class="dropdown-item" href="<?= $base_url ?>/frontend/pesanan.php">
                                    <i class="fas fa-receipt mr-2"></i> Pesanan Saya
                                </a>
                                <div class="dropdown-divider"></div>
                            <?php endif; ?>
                            <a class="dropdown-item" href="<?= $base_url ?>/frontend/proses/proses_logout.php">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item ml-lg-2">
                        <a class="nav-link btn-nav-login" href="<?= $base_url ?>/frontend/login.php">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="container mt-3">
        <div class="alert-cipamilk alert-success-cipamilk">
            <i class="fas fa-check-circle"></i>
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="container mt-3">
        <div class="alert-cipamilk alert-danger-cipamilk">
            <i class="fas fa-exclamation-circle"></i>
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    </div>
<?php endif; ?>
