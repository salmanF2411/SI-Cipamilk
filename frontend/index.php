<?php
/**
 * Landing Page — Cipamilk E-Commerce
 */
$page_title = 'Cipamilk — Olahan Susu Segar Berkualitas';
require_once __DIR__ . '/../config/database.php';

// Ambil produk unggulan (4 produk)
$stmt = $pdo->query("SELECT p.*, c.nama_kategori FROM products p JOIN categories c ON p.id_category = c.id_category ORDER BY p.created_at DESC LIMIT 4");
$produk_unggulan = $stmt->fetchAll();

// Ambil statistik
$total_produk = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section" style="padding: 60px 0; overflow: hidden;">
    <div class="container">
        <div class="row align-items-center">
            <!-- Kolom Kiri: Teks & Informasi -->
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="hero-content pr-lg-3">
                    <div class="hero-badge">
                        <i class="fas fa-leaf"></i> 100% Susu Segar Alami
                    </div>
                    <h1 class="hero-title">
                        Kesegaran Olahan Susu <span>Cipamilk</span> Untuk Kebutuhan Anda
                    </h1>
                    <p class="hero-subtitle">
                        Nikmati produk olahan susu segar berkualitas langsung dari peternakan.
                        Mulai dari susu murni, yogurt, keju, hingga es krim — semua dibuat dengan cinta untuk keluarga
                        Indonesia.
                    </p>
                    <div class="hero-buttons">
                        <a href="<?= $base_url ?>/frontend/produk.php" class="btn btn-primary-cipamilk">
                            <i class="fas fa-box-open mr-2"></i> Lihat Produk
                        </a>
                        <a href="<?= $base_url ?>/frontend/subscription.php" class="btn btn-accent-cipamilk">
                            <i class="fas fa-calendar-check mr-2"></i> Mulai Subscription
                        </a>
                    </div>
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <div class="hero-stat-number"><?= $total_produk ?>+</div>
                            <div class="hero-stat-label">Pilihan Produk</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-number">100%</div>
                            <div class="hero-stat-label">Alami & Segar</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-number">Grade A</div>
                            <div class="hero-stat-label">Kualitas Terjamin</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Foto Produk Lebih Besar & Pas -->
            <div class="col-lg-6">
                <div class="hero-image-container"
                    style="position: relative; width: 100%; max-width: 540px; margin: 0 auto;">

                    <!-- Frame Gambar Utama -->
                    <div
                        style="width: 100%; height: 440px; border-radius: 28px; overflow: hidden; box-shadow: 0 20px 40px -10px rgba(77, 168, 218, 0.28); border: 4px solid #ffffff; background: #fff;">
                        <img src="<?= $base_url ?>/assets/images/uploads/cipamilk.jpg"
                            alt="Cipamilk Fresh Dairy Products"
                            style="width: 100%; height: 100%; object-fit: cover; object-position: center; display: block;"
                            onerror="this.parentElement.innerHTML='<div style=\'width:100%;height:100%;background:linear-gradient(135deg,rgba(77,168,218,0.1),rgba(244,201,93,0.1));border-radius:24px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:1rem;\'><i class=\'fas fa-glass-whiskey\' style=\'font-size:5rem;color:#4DA8DA;\'></i><span style=\'font-family:Poppins;font-weight:700;color:#4DA8DA;font-size:1.5rem;\'>Cipamilk</span><span style=\'color:#636E72;\'>Olahan Susu Segar</span></div>'">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Produk Unggulan -->
<section class="section-padding section-white">
    <div class="container">
        <h2 class="section-title">Produk <span>Unggulan</span></h2>
        <p class="section-subtitle">Produk olahan susu terbaik pilihan pelanggan kami</p>

        <div class="row">
            <?php foreach ($produk_unggulan as $index => $p): ?>
                <div class="col-lg-3 col-md-6 mb-4 reveal-on-scroll" style="transition-delay: <?= $index * 0.1 ?>s">
                    <div class="product-card"
                        data-href="<?= $base_url ?>/frontend/detail_produk.php?id=<?= $p['id_product'] ?>"
                        onclick="if(!event.target.closest('a, button')) window.location.href='<?= $base_url ?>/frontend/detail_produk.php?id=<?= $p['id_product'] ?>'">
                        <div class="product-card-image">
                            <img src="<?= $base_url ?>/assets/images/products/<?= htmlspecialchars($p['gambar']) ?>"
                                alt="<?= htmlspecialchars($p['nama_produk']) ?>">
                            <?php if ($index === 0): ?>
                                <span class="product-card-badge"><i class="fas fa-fire mr-1"></i> Terlaris</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-card-body">
                            <span class="product-card-category"><?= htmlspecialchars($p['nama_kategori']) ?></span>
                            <h5 class="product-card-title"><?= htmlspecialchars($p['nama_produk']) ?></h5>
                            <p class="product-card-desc"><?= htmlspecialchars($p['deskripsi']) ?></p>
                            <div class="product-card-footer">
                                <span class="product-card-price"><?= formatRupiah($p['harga']) ?></span>
                                <a href="<?= $base_url ?>/frontend/detail_produk.php?id=<?= $p['id_product'] ?>"
                                    class="btn btn-primary-cipamilk btn-sm">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
            <a href="<?= $base_url ?>/frontend/produk.php" class="btn btn-outline-cipamilk">
                Lihat Semua Produk <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Subscription Banner -->
<section class="section-padding section-cream">
    <div class="container">
        <div class="subscription-banner reveal-on-scroll">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h2><i class="fas fa-calendar-alt mr-2"></i> Berlangganan Produk Cipamilk</h2>
                    <p class="mb-0">
                        Dapatkan produk susu segar favorit Anda secara rutin! Pilih jadwal pengiriman harian, mingguan,
                        atau bulanan
                        sesuai kebutuhan keluarga Anda. Praktis dan tanpa repot.
                    </p>
                </div>
                <div class="col-lg-5 text-lg-right mt-3 mt-lg-0">
                    <a href="<?= $base_url ?>/frontend/subscription.php" class="btn btn-primary-cipamilk btn-lg">
                        <i class="fas fa-rocket mr-2"></i> Mulai Berlangganan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tentang Cipamilk -->
<section class="section-padding about-section" id="tentang">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0 reveal-on-scroll">
                <div class="about-image">
                    <img src="<?= $base_url ?>/assets/images/uploads/cipamilk.jpg" alt="Tentang Cipamilk"
                        class="img-fluid"
                        onerror="this.parentElement.innerHTML='<div style=\'width:100%;height:350px;background:linear-gradient(135deg,rgba(79,119,45,0.1),rgba(244,201,93,0.1));border-radius:16px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:1rem;\'><i class=\'fas fa-tractor\' style=\'font-size:4rem;color:#4F772D;\'></i><span style=\'font-family:Poppins;font-weight:700;color:#4F772D;font-size:1.2rem;\'>Peternakan Cipamilk</span></div>'">
                </div>
            </div>
            <div class="col-lg-6 reveal-on-scroll" style="transition-delay: 0.2s">
                <h2 class="section-title text-left mb-3">Tentang <span>Cipamilk</span></h2>
                <p class="text-muted mb-4">
                    Cipamilk hadir untuk menyediakan produk olahan susu segar berkualitas tinggi langsung dari
                    peternakan
                    ke meja makan keluarga Indonesia. Kami berkomitmen menjaga kualitas dan kesegaran setiap produk.
                </p>

                <div class="about-feature">
                    <div class="about-feature-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div>
                        <h5>100% Alami</h5>
                        <p>Tanpa bahan pengawet dan pewarna buatan</p>
                    </div>
                </div>

                <div class="about-feature">
                    <div class="about-feature-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div>
                        <h5>Pengiriman Cepat</h5>
                        <p>Produk diantar segar ke rumah Anda</p>
                    </div>
                </div>

                <div class="about-feature">
                    <div class="about-feature-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div>
                        <h5>Terjamin Kualitas</h5>
                        <p>Proses produksi terstandardisasi dan higienis</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>