<?php
/**
 * Proses Keranjang — Cipamilk E-Commerce
 */
require_once __DIR__ . '/../../config/database.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'customer') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    redirect($base_url . '/frontend/login.php');
}

$id_customer = $_SESSION['id_customer'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        $id_product = (int)($_POST['id_product'] ?? 0);
        $jumlah     = (int)($_POST['jumlah'] ?? 1);

        if ($id_product <= 0 || $jumlah <= 0) {
            $_SESSION['error'] = 'Data tidak valid.';
            redirect($base_url . '/frontend/produk.php');
        }

        // Cek stok
        $stmt = $pdo->prepare("SELECT stok FROM products WHERE id_product = ?");
        $stmt->execute([$id_product]);
        $product = $stmt->fetch();

        if (!$product || $product['stok'] < $jumlah) {
            $_SESSION['error'] = 'Stok tidak mencukupi.';
            redirect($base_url . '/frontend/detail_produk.php?id=' . $id_product);
        }

        // Cek apakah sudah ada di cart
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE id_customer = ? AND id_product = ?");
        $stmt->execute([$id_customer, $id_product]);
        $existing = $stmt->fetch();

        if ($existing) {
            $new_qty = $existing['jumlah'] + $jumlah;
            $stmt = $pdo->prepare("UPDATE cart SET jumlah = ? WHERE id_cart = ?");
            $stmt->execute([$new_qty, $existing['id_cart']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO cart (id_customer, id_product, jumlah) VALUES (?, ?, ?)");
            $stmt->execute([$id_customer, $id_product, $jumlah]);
        }

        $_SESSION['success'] = 'Produk berhasil ditambahkan ke keranjang!';
        redirect($base_url . '/frontend/keranjang.php');
        break;

    case 'update':
        $id_cart = (int)($_POST['id_cart'] ?? 0);
        $jumlah  = (int)($_POST['jumlah'] ?? 1);

        if ($jumlah <= 0) {
            // Hapus jika jumlah 0
            $stmt = $pdo->prepare("DELETE FROM cart WHERE id_cart = ? AND id_customer = ?");
            $stmt->execute([$id_cart, $id_customer]);
        } else {
            $stmt = $pdo->prepare("UPDATE cart SET jumlah = ? WHERE id_cart = ? AND id_customer = ?");
            $stmt->execute([$jumlah, $id_cart, $id_customer]);
        }

        redirect($base_url . '/frontend/keranjang.php');
        break;

    case 'delete':
        $id_cart = (int)($_GET['id_cart'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id_cart = ? AND id_customer = ?");
        $stmt->execute([$id_cart, $id_customer]);

        $_SESSION['success'] = 'Produk berhasil dihapus dari keranjang.';
        redirect($base_url . '/frontend/keranjang.php');
        break;

    default:
        redirect($base_url . '/frontend/keranjang.php');
}
