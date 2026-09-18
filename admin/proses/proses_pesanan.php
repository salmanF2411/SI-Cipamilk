<?php
/**
 * Proses Pesanan Admin — Cipamilk E-Commerce
 */
require_once __DIR__ . '/../../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect($base_url . '/frontend/login.php');
}

$action = $_GET['action'] ?? '';

if ($action === 'update') {
    $id     = (int)($_GET['id'] ?? 0);
    $status = sanitize($_GET['status'] ?? '');

    $valid_statuses = ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'];
    if (!in_array($status, $valid_statuses)) {
        $_SESSION['error'] = 'Status tidak valid.';
        redirect($base_url . '/admin/pesanan.php');
    }

    // Ambil data dan status pesanan saat ini
    $stmt = $pdo->prepare("SELECT status FROM orders WHERE id_order = ?");
    $stmt->execute([$id]);
    $order = $stmt->fetch();

    if (!$order) {
        $_SESSION['error'] = 'Pesanan tidak ditemukan.';
        redirect($base_url . '/admin/pesanan.php');
    }

    $old_status = $order['status'];

    if ($old_status !== $status) {
        $pdo->beginTransaction();
        try {
            // Jika status berubah menjadi 'selesai', kurangi stok produk (menandakan produk sampai ke konsumen)
            if ($old_status !== 'selesai' && $status === 'selesai') {
                $stmt_items = $pdo->prepare("SELECT id_product, jumlah FROM order_details WHERE id_order = ?");
                $stmt_items->execute([$id]);
                $items = $stmt_items->fetchAll();

                foreach ($items as $item) {
                    $stmt_stock = $pdo->prepare("UPDATE products SET stok = GREATEST(0, stok - ?) WHERE id_product = ?");
                    $stmt_stock->execute([$item['jumlah'], $item['id_product']]);
                }
            }

            // Jika status sebelumnya 'selesai' lalu diubah ke status lain (misal dibatalkan), kembalikan stok produk
            if ($old_status === 'selesai' && $status !== 'selesai') {
                $stmt_items = $pdo->prepare("SELECT id_product, jumlah FROM order_details WHERE id_order = ?");
                $stmt_items->execute([$id]);
                $items = $stmt_items->fetchAll();

                foreach ($items as $item) {
                    $stmt_stock = $pdo->prepare("UPDATE products SET stok = stok + ? WHERE id_product = ?");
                    $stmt_stock->execute([$item['jumlah'], $item['id_product']]);
                }
            }

            // Update status pesanan
            $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id_order = ?");
            $stmt->execute([$status, $id]);

            $pdo->commit();

            if ($status === 'selesai') {
                $_SESSION['success'] = 'Status pesanan #ORD-' . str_pad($id, 5, '0', STR_PAD_LEFT) . ' berhasil diselesaikan! Stok produk telah dikurangi karena pesanan telah sampai ke konsumen.';
            } else {
                $_SESSION['success'] = 'Status pesanan #ORD-' . str_pad($id, 5, '0', STR_PAD_LEFT) . ' berhasil diupdate menjadi "' . ucfirst($status) . '".';
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = 'Gagal memperbarui status pesanan: ' . $e->getMessage();
        }
    } else {
        $_SESSION['success'] = 'Status pesanan sudah dalam status "' . ucfirst($status) . '".';
    }
}

redirect($base_url . '/admin/pesanan.php');
