<?php
/**
 * Proses Checkout — Cipamilk E-Commerce
 */
require_once __DIR__ . '/../../config/database.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'customer') {
    redirect($base_url . '/frontend/login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($base_url . '/frontend/keranjang.php');
}

$id_customer    = $_SESSION['id_customer'];
$alamat         = sanitize($_POST['alamat'] ?? '');
$nomor_telepon  = sanitize($_POST['nomor_telepon'] ?? '');

if (empty($alamat) || empty($nomor_telepon)) {
    $_SESSION['error'] = 'Alamat dan nomor telepon harus diisi.';
    redirect($base_url . '/frontend/checkout.php');
}

// Ambil item keranjang
$stmt = $pdo->prepare("
    SELECT c.*, p.harga, p.stok
    FROM cart c
    JOIN products p ON c.id_product = p.id_product
    WHERE c.id_customer = ?
");
$stmt->execute([$id_customer]);
$cart_items = $stmt->fetchAll();

if (count($cart_items) === 0) {
    $_SESSION['error'] = 'Keranjang kosong.';
    redirect($base_url . '/frontend/keranjang.php');
}

try {
    $pdo->beginTransaction();

    // Hitung total
    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['harga'] * $item['jumlah'];
    }

    // Insert order
    $stmt = $pdo->prepare("INSERT INTO orders (id_customer, total, status, alamat_pengiriman, nomor_telepon) VALUES (?, ?, 'pending', ?, ?)");
    $stmt->execute([$id_customer, $total, $alamat, $nomor_telepon]);
    $id_order = $pdo->lastInsertId();

    // Insert order details & kurangi stok
    foreach ($cart_items as $item) {
        $subtotal = $item['harga'] * $item['jumlah'];
        
        $stmt = $pdo->prepare("INSERT INTO order_details (id_order, id_product, jumlah, subtotal) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id_order, $item['id_product'], $item['jumlah'], $subtotal]);

        // Kurangi stok
        $stmt = $pdo->prepare("UPDATE products SET stok = stok - ? WHERE id_product = ?");
        $stmt->execute([$item['jumlah'], $item['id_product']]);
    }

    // Hapus keranjang
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id_customer = ?");
    $stmt->execute([$id_customer]);

    $pdo->commit();

    $_SESSION['success'] = 'Pesanan berhasil dibuat! No. Pesanan: #ORD-' . str_pad($id_order, 5, '0', STR_PAD_LEFT);
    redirect($base_url . '/frontend/pesanan.php');

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = 'Terjadi kesalahan saat membuat pesanan.';
    redirect($base_url . '/frontend/checkout.php');
}
