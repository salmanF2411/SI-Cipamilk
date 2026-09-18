<?php
/**
 * Proses Produk Admin — Cipamilk E-Commerce
 */
require_once __DIR__ . '/../../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect($base_url . '/frontend/login.php');
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        $nama      = sanitize($_POST['nama_produk'] ?? '');
        $id_cat    = (int)($_POST['id_category'] ?? 0);
        $harga     = (float)($_POST['harga'] ?? 0);
        $stok      = (int)($_POST['stok'] ?? 0);
        $deskripsi = sanitize($_POST['deskripsi'] ?? '');
        $gambar    = '';

        // Upload gambar
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $gambar = uniqid('prod_') . '.' . $ext;
            $upload_path = __DIR__ . '/../../assets/images/products/' . $gambar;
            
            if (!is_dir(dirname($upload_path))) {
                mkdir(dirname($upload_path), 0755, true);
            }
            move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path);
        }

        $stmt = $pdo->prepare("INSERT INTO products (id_category, nama_produk, harga, stok, gambar, deskripsi) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_cat, $nama, $harga, $stok, $gambar, $deskripsi]);

        $_SESSION['success'] = 'Produk berhasil ditambahkan!';
        redirect($base_url . '/admin/produk.php');
        break;

    case 'update':
        $id        = (int)($_POST['id_product'] ?? 0);
        $nama      = sanitize($_POST['nama_produk'] ?? '');
        $id_cat    = (int)($_POST['id_category'] ?? 0);
        $harga     = (float)($_POST['harga'] ?? 0);
        $stok      = (int)($_POST['stok'] ?? 0);
        $deskripsi = sanitize($_POST['deskripsi'] ?? '');
        $gambar    = $_POST['gambar_lama'] ?? '';

        // Upload gambar baru
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $gambar = uniqid('prod_') . '.' . $ext;
            $upload_path = __DIR__ . '/../../assets/images/products/' . $gambar;
            
            if (!is_dir(dirname($upload_path))) {
                mkdir(dirname($upload_path), 0755, true);
            }
            move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path);
        }

        $stmt = $pdo->prepare("UPDATE products SET id_category = ?, nama_produk = ?, harga = ?, stok = ?, gambar = ?, deskripsi = ? WHERE id_product = ?");
        $stmt->execute([$id_cat, $nama, $harga, $stok, $gambar, $deskripsi, $id]);

        $_SESSION['success'] = 'Produk berhasil diupdate!';
        redirect($base_url . '/admin/produk.php');
        break;

    case 'delete':
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM products WHERE id_product = ?");
        $stmt->execute([$id]);

        $_SESSION['success'] = 'Produk berhasil dihapus!';
        redirect($base_url . '/admin/produk.php');
        break;

    default:
        redirect($base_url . '/admin/produk.php');
}
