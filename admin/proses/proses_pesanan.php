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

    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id_order = ?");
    $stmt->execute([$status, $id]);

    $_SESSION['success'] = 'Status pesanan berhasil diupdate!';
}

redirect($base_url . '/admin/pesanan.php');
