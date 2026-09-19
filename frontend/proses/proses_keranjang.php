<?php
/**
 * Proses Keranjang — Cipamilk E-Commerce
 */
require_once __DIR__ . '/../../config/database.php';

$is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (isset($_POST['ajax']) && $_POST['ajax'] == '1')
    || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

if (!isLoggedIn() || $_SESSION['role'] !== 'customer') {
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Silakan login terlebih dahulu untuk menambahkan ke keranjang.',
            'redirect' => $base_url . '/frontend/login.php'
        ]);
        exit;
    }
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    redirect($base_url . '/frontend/login.php');
}

$id_customer = $_SESSION['id_customer'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        $id_product = (int) ($_POST['id_product'] ?? 0);
        $jumlah = (int) ($_POST['jumlah'] ?? 1);

        if ($id_product <= 0 || $jumlah <= 0) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Data produk tidak valid.']);
                exit;
            }
            $_SESSION['error'] = 'Data tidak valid.';
            redirect($base_url . '/frontend/produk.php');
        }

        // Cek stok
        $stmt = $pdo->prepare("SELECT stok, nama_produk FROM products WHERE id_product = ?");
        $stmt->execute([$id_product]);
        $product = $stmt->fetch();

        if (!$product || $product['stok'] < $jumlah) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Stok produk tidak mencukupi.']);
                exit;
            }
            $_SESSION['error'] = 'Stok tidak mencukupi.';
            redirect($base_url . '/frontend/detail_produk.php?id=' . $id_product);
        }

        // Cek apakah sudah ada di cart
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE id_customer = ? AND id_product = ?");
        $stmt->execute([$id_customer, $id_product]);
        $existing = $stmt->fetch();

        if ($existing) {
            $new_qty = $existing['jumlah'] + $jumlah;
            if ($new_qty > $product['stok']) {
                if ($is_ajax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Total di keranjang melebihi stok yang tersedia (maksimal ' . $product['stok'] . ').']);
                    exit;
                }
                $_SESSION['error'] = 'Total di keranjang melebihi stok yang tersedia.';
                redirect($base_url . '/frontend/detail_produk.php?id=' . $id_product);
            }
            $stmt = $pdo->prepare("UPDATE cart SET jumlah = ? WHERE id_cart = ?");
            $stmt->execute([$new_qty, $existing['id_cart']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO cart (id_customer, id_product, jumlah) VALUES (?, ?, ?)");
            $stmt->execute([$id_customer, $id_product, $jumlah]);
        }

        // Hitung total item baru di cart
        $stmt_count = $pdo->prepare("SELECT SUM(jumlah) as total FROM cart WHERE id_customer = ?");
        $stmt_count->execute([$id_customer]);
        $cart_count = (int) ($stmt_count->fetch()['total'] ?? 0);

        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'cart_count' => $cart_count,
                'product_name' => $product['nama_produk']
            ]);
            exit;
        }

        $_SESSION['success'] = 'Produk berhasil ditambahkan ke keranjang!';
        $redirect_to = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ($base_url . '/frontend/detail_produk.php?id=' . $id_product);
        redirect($redirect_to);
        break;

    case 'update':
        $id_cart = (int) ($_POST['id_cart'] ?? 0);
        $jumlah = (int) ($_POST['jumlah'] ?? 1);

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
        $id_cart = (int) ($_GET['id_cart'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id_cart = ? AND id_customer = ?");
        $stmt->execute([$id_cart, $id_customer]);

        $_SESSION['success'] = 'Produk berhasil dihapus dari keranjang.';
        redirect($base_url . '/frontend/keranjang.php');
        break;

    default:
        redirect($base_url . '/frontend/keranjang.php');
}
