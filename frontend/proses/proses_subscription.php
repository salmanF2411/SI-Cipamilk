<?php
/**
 * Proses Subscription — Cipamilk E-Commerce
 */
require_once __DIR__ . '/../../config/database.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'customer') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    redirect($base_url . '/frontend/login.php');
}

$id_customer = $_SESSION['id_customer'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect($base_url . '/frontend/subscription.php');
        }

        $id_product = (int) ($_POST['id_product'] ?? 0);
        $periode = sanitize($_POST['periode'] ?? '');
        $tanggal_mulai = sanitize($_POST['tanggal_mulai'] ?? '');

        if ($id_product <= 0 || empty($periode) || empty($tanggal_mulai)) {
            $_SESSION['error'] = 'Semua field harus diisi.';
            redirect($base_url . '/frontend/subscription.php');
        }

        if (!in_array($periode, ['harian', 'mingguan', 'bulanan'])) {
            $_SESSION['error'] = 'Periode tidak valid.';
            redirect($base_url . '/frontend/subscription.php');
        }

        $stmt = $pdo->prepare("INSERT INTO subscriptions (id_customer, id_product, periode, tanggal_mulai, status) VALUES (?, ?, ?, ?, 'aktif')");
        $stmt->execute([$id_customer, $id_product, $periode, $tanggal_mulai]);

        $_SESSION['success'] = 'Subscription berhasil dibuat!';
        redirect($base_url . '/frontend/subscription.php');
        break;

    case 'cancel':
        $id = (int) ($_GET['id'] ?? 0);

        $stmt = $pdo->prepare("UPDATE subscriptions SET status = 'dibatalkan' WHERE id_subscription = ? AND id_customer = ?");
        $stmt->execute([$id, $id_customer]);

        $_SESSION['success'] = 'Subscription berhasil dibatalkan.';
        redirect($base_url . '/frontend/subscription.php');
        break;

    default:
        redirect($base_url . '/frontend/subscription.php');
}
