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

$id_customer = $_SESSION['id_customer'];
$alamat = sanitize($_POST['alamat'] ?? '');
$nomor_telepon = sanitize($_POST['nomor_telepon'] ?? '');
$metode_pembayaran = sanitize($_POST['metode_pembayaran'] ?? 'cod');

// Validasi metode pembayaran
if (!in_array($metode_pembayaran, ['cod', 'qris'])) {
    $metode_pembayaran = 'cod';
}

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
    $stmt = $pdo->prepare("INSERT INTO orders (id_customer, total, status, alamat_pengiriman, nomor_telepon, metode_pembayaran) VALUES (?, ?, 'pending', ?, ?, ?)");
    $stmt->execute([$id_customer, $total, $alamat, $nomor_telepon, $metode_pembayaran]);
    $id_order = $pdo->lastInsertId();

    // Insert order details (stok belum dikurangi sampai status pesanan menjadi 'selesai')
    foreach ($cart_items as $item) {
        $subtotal = $item['harga'] * $item['jumlah'];

        $stmt = $pdo->prepare("INSERT INTO order_details (id_order, id_product, jumlah, subtotal) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id_order, $item['id_product'], $item['jumlah'], $subtotal]);
    }

    // Hapus keranjang
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id_customer = ?");
    $stmt->execute([$id_customer]);

    $pdo->commit();

    $_SESSION['order_success'] = [
        'id_order' => $id_order,
        'order_number' => '#ORD-' . str_pad($id_order, 5, '0', STR_PAD_LEFT),
        'total' => $total,
        'alamat' => $alamat,
        'nomor_telepon' => $nomor_telepon,
        'metode_pembayaran' => $metode_pembayaran,
        'items_count' => count($cart_items)
    ];
    $_SESSION['success'] = 'Pesanan berhasil dibuat! No. Pesanan: #ORD-' . str_pad($id_order, 5, '0', STR_PAD_LEFT);
    redirect($base_url . '/frontend/pesanan.php');

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = 'Terjadi kesalahan saat membuat pesanan.';
    redirect($base_url . '/frontend/checkout.php');
}
