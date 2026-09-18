<?php
/**
 * Proses Login — Cipamilk E-Commerce
 */
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($base_url . '/frontend/login.php');
}

$email    = sanitize($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['error'] = 'Email dan password harus diisi.';
    redirect($base_url . '/frontend/login.php');
}

// Cari user
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['error'] = 'Email atau password salah.';
    redirect($base_url . '/frontend/login.php');
}

// Set session
$_SESSION['id_user'] = $user['id_user'];
$_SESSION['nama']    = $user['nama'];
$_SESSION['email']   = $user['email'];
$_SESSION['role']    = $user['role'];

// Jika customer, ambil id_customer
if ($user['role'] === 'customer') {
    $stmt_cust = $pdo->prepare("SELECT id_customer FROM customers WHERE id_user = ?");
    $stmt_cust->execute([$user['id_user']]);
    $customer = $stmt_cust->fetch();
    $_SESSION['id_customer'] = $customer['id_customer'] ?? null;
}

$_SESSION['success'] = 'Selamat datang, ' . htmlspecialchars($user['nama']) . '!';

if ($user['role'] === 'admin') {
    redirect($base_url . '/admin/index.php');
} else {
    redirect($base_url . '/frontend/index.php');
}
