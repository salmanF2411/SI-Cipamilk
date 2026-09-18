<?php
/**
 * Proses Kategori Admin — Cipamilk E-Commerce
 */
require_once __DIR__ . '/../../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect($base_url . '/frontend/login.php');
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        $nama = sanitize($_POST['nama_kategori'] ?? '');
        if (empty($nama)) {
            $_SESSION['error'] = 'Nama kategori harus diisi.';
            redirect($base_url . '/admin/kategori.php');
        }

        $stmt = $pdo->prepare("INSERT INTO categories (nama_kategori) VALUES (?)");
        $stmt->execute([$nama]);

        $_SESSION['success'] = 'Kategori berhasil ditambahkan!';
        redirect($base_url . '/admin/kategori.php');
        break;

    case 'update':
        $id   = (int)($_POST['id_category'] ?? 0);
        $nama = sanitize($_POST['nama_kategori'] ?? '');

        $stmt = $pdo->prepare("UPDATE categories SET nama_kategori = ? WHERE id_category = ?");
        $stmt->execute([$nama, $id]);

        $_SESSION['success'] = 'Kategori berhasil diupdate!';
        redirect($base_url . '/admin/kategori.php');
        break;

    case 'delete':
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id_category = ?");
        $stmt->execute([$id]);

        $_SESSION['success'] = 'Kategori berhasil dihapus!';
        redirect($base_url . '/admin/kategori.php');
        break;

    default:
        redirect($base_url . '/admin/kategori.php');
}
