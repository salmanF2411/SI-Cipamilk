<?php
/**
 * Proses Subscription Admin — Cipamilk E-Commerce
 */
require_once __DIR__ . '/../../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect($base_url . '/frontend/login.php');
}

$action = $_GET['action'] ?? '';

if ($action === 'update') {
    $id     = (int)($_GET['id'] ?? 0);
    $status = sanitize($_GET['status'] ?? '');

    $valid_statuses = ['aktif', 'selesai', 'dibatalkan'];
    if (!in_array($status, $valid_statuses)) {
        $_SESSION['error'] = 'Status tidak valid.';
        redirect($base_url . '/admin/subscription.php');
    }

    $stmt = $pdo->prepare("UPDATE subscriptions SET status = ? WHERE id_subscription = ?");
    $stmt->execute([$status, $id]);

    $_SESSION['success'] = 'Status subscription berhasil diupdate!';
}

redirect($base_url . '/admin/subscription.php');
