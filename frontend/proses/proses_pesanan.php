<?php
/**
 * Proses Pesanan Customer — Cipamilk E-Commerce
 */
require_once __DIR__ . '/../../config/database.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'customer') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    redirect($base_url . '/frontend/login.php');
}

$id_customer = $_SESSION['id_customer'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'complete') {
    $id = (int) ($_POST['id_order'] ?? $_GET['id'] ?? 0);

    // Ambil pesanan dan pastikan milik customer ini
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id_order = ? AND id_customer = ?");
    $stmt->execute([$id, $id_customer]);
    $order = $stmt->fetch();

    if (!$order) {
        $_SESSION['error'] = 'Pesanan tidak ditemukan.';
        redirect($base_url . '/frontend/pesanan.php');
    }

    if ($order['status'] === 'selesai') {
        $_SESSION['success'] = 'Pesanan ini sudah selesai.';
        redirect($base_url . '/frontend/pesanan.php');
    }

    $pdo->beginTransaction();
    try {
        // Kurangi stok karena produk telah sampai ke tangan konsumen
        $stmt_items = $pdo->prepare("SELECT id_product, jumlah FROM order_details WHERE id_order = ?");
        $stmt_items->execute([$id]);
        $items = $stmt_items->fetchAll();

        foreach ($items as $item) {
            $stmt_stock = $pdo->prepare("UPDATE products SET stok = GREATEST(0, stok - ?) WHERE id_product = ?");
            $stmt_stock->execute([$item['jumlah'], $item['id_product']]);
        }

        // Update status menjadi selesai
        $stmt = $pdo->prepare("UPDATE orders SET status = 'selesai' WHERE id_order = ?");
        $stmt->execute([$id]);

        $pdo->commit();
        $_SESSION['success'] = 'Terima kasih! Pesanan #ORD-' . str_pad($id, 5, '0', STR_PAD_LEFT) . ' telah Anda konfirmasi diterima dan selesai.';
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = 'Gagal mengonfirmasi penerimaan pesanan: ' . $e->getMessage();
    }
}

redirect($base_url . '/frontend/pesanan.php');
