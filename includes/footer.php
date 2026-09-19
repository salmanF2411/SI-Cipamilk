<!-- Footer -->
<footer class="footer-cipamilk">
    <div class="container">
        <div class="row">
            <!-- Brand -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5><i class="fas fa-cow mr-2" style="color: var(--accent);"></i> Cipamilk</h5>
                <p>
                    Cipamilk menyediakan produk olahan susu segar berkualitas langsung dari peternakan.
                    Kesegaran dan kualitas adalah prioritas kami untuk keluarga Indonesia.
                </p>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5>Menu</h5>
                <ul class="footer-links">
                    <li><a href="<?= $base_url ?>/frontend/index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                    <li><a href="<?= $base_url ?>/frontend/produk.php"><i class="fas fa-chevron-right"></i> Produk</a>
                    </li>
                    <li><a href="<?= $base_url ?>/frontend/subscription.php"><i class="fas fa-chevron-right"></i>
                            Subscription</a></li>
                    <li><a href="<?= $base_url ?>/frontend/login.php"><i class="fas fa-chevron-right"></i> Login</a>
                    </li>
                </ul>
            </div>

            <!-- Kategori -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Kategori</h5>
                <ul class="footer-links">
                    <li><a href="<?= $base_url ?>/frontend/produk.php?kategori=1"><i class="fas fa-chevron-right"></i>
                            Susu Segar</a></li>
                    <li><a href="<?= $base_url ?>/frontend/produk.php?kategori=2"><i class="fas fa-chevron-right"></i>
                            Yogurt</a></li>
                    <li><a href="<?= $base_url ?>/frontend/produk.php?kategori=3"><i class="fas fa-chevron-right"></i>
                            Keju</a></li>
                    <li><a href="<?= $base_url ?>/frontend/produk.php?kategori=4"><i class="fas fa-chevron-right"></i>
                            Es Krim</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Kontak</h5>
                <ul class="footer-contact list-unstyled">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Jl. Peternakan No. 10, Cipamilk, Bandung</span>
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <span>(022) 1234-5678</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>info@cipamilk.com</span>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        <span>Senin - Sabtu: 08.00 - 17.00</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom -->
        <div class="footer-bottom">
            <p class="mb-0">&copy; <?= date('Y') ?> <strong>Cipamilk</strong>. All rights reserved. | Olahan Susu Segar
                Berkualitas</p>
        </div>
    </div>
</footer>

<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<!-- Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<!-- Bootstrap 4 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<!-- Custom JS -->
<script src="<?= $base_url ?>/assets/js/script.js"></script>

</body>

</html>