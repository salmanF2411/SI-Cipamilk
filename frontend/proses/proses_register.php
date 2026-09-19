<?php
/**
 * Proses Register — Cipamilk E-Commerce
 */
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($base_url . '/frontend/register.php');
}

$nama = sanitize($_POST['nama'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi_password'] ?? '';
$nomor_telepon = sanitize($_POST['nomor_telepon'] ?? '');
$alamat = sanitize($_POST['alamat'] ?? '');

// Validasi
if (empty($nama) || empty($email) || empty($password) || empty($nomor_telepon) || empty($alamat)) {
    $_SESSION['error'] = 'Semua field harus diisi.';
    redirect($base_url . '/frontend/register.php');
}

if (strlen($password) < 6) {
    $_SESSION['error'] = 'Password minimal 6 karakter.';
    redirect($base_url . '/frontend/register.php');
}

if ($password !== $konfirmasi) {
    $_SESSION['error'] = 'Konfirmasi password tidak cocok.';
    redirect($base_url . '/frontend/register.php');
}

// Cek email duplikat
$stmt = $pdo->prepare("SELECT id_user FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    $_SESSION['error'] = 'Email sudah terdaftar.';
    redirect($base_url . '/frontend/register.php');
}

try {
    $pdo->beginTransaction();

    // Insert user
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'customer')");
    $stmt->execute([$nama, $email, $hashed]);
    $id_user = $pdo->lastInsertId();

    // Insert customer
    $stmt = $pdo->prepare("INSERT INTO customers (id_user, alamat, nomor_telepon) VALUES (?, ?, ?)");
    $stmt->execute([$id_user, $alamat, $nomor_telepon]);

    $pdo->commit();

    $_SESSION['success'] = 'Registrasi berhasil! Silakan login.';
    redirect($base_url . '/frontend/login.php');

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = 'Terjadi kesalahan saat registrasi.';
    redirect($base_url . '/frontend/register.php');
}
