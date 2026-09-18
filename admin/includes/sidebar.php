<!-- Main Sidebar -->
<aside class="main-sidebar elevation-4">
    <!-- Brand Logo -->
    <a href="<?= $base_url ?>/admin/index.php" class="brand-link text-center">
        <i class="fas fa-cow" style="color: var(--accent); font-size: 1.5rem;"></i>
        <span class="brand-text ml-2">Cipamilk Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <div style="width:35px;height:35px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;">
                    <?= strtoupper(substr($_SESSION['nama'] ?? 'A', 0, 1)) ?>
                </div>
            </div>
            <div class="info">
                <a href="#"><?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="<?= $base_url ?>/admin/index.php" class="nav-link <?= $admin_page === 'index' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-header" style="color: rgba(255,255,255,0.4); font-size: 0.75rem; padding: 0.5rem 1.5rem;">KELOLA DATA</li>
                <li class="nav-item">
                    <a href="<?= $base_url ?>/admin/produk.php" class="nav-link <?= $admin_page === 'produk' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-box-open"></i>
                        <p>Produk</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base_url ?>/admin/kategori.php" class="nav-link <?= $admin_page === 'kategori' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Kategori</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base_url ?>/admin/customer.php" class="nav-link <?= $admin_page === 'customer' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Customer</p>
                    </a>
                </li>
                <li class="nav-header" style="color: rgba(255,255,255,0.4); font-size: 0.75rem; padding: 0.5rem 1.5rem;">TRANSAKSI</li>
                <li class="nav-item">
                    <a href="<?= $base_url ?>/admin/pesanan.php" class="nav-link <?= $admin_page === 'pesanan' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-shopping-bag"></i>
                        <p>Pesanan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base_url ?>/admin/subscription.php" class="nav-link <?= $admin_page === 'subscription' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Subscription</p>
                    </a>
                </li>
                <li class="nav-header" style="color: rgba(255,255,255,0.4); font-size: 0.75rem; padding: 0.5rem 1.5rem;">LAPORAN</li>
                <li class="nav-item">
                    <a href="<?= $base_url ?>/admin/laporan.php" class="nav-link <?= $admin_page === 'laporan' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Laporan</p>
                    </a>
                </li>
                <li class="nav-header" style="color: rgba(255,255,255,0.4); font-size: 0.75rem; padding: 0.5rem 1.5rem;">SISTEM</li>
                <li class="nav-item">
                    <a href="<?= $base_url ?>/frontend/index.php" class="nav-link" target="_blank">
                        <i class="nav-icon fas fa-store"></i>
                        <p>Lihat Toko</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base_url ?>/admin/proses/proses_logout.php" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
