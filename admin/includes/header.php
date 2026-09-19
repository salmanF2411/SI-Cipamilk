<?php
/**
 * Admin Header — Cipamilk E-Commerce (AdminLTE)
 */
if (!isset($base_url)) {
    require_once __DIR__ . '/../../config/database.php';
}

if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['error'] = 'Akses ditolak.';
    redirect($base_url . '/frontend/login.php');
}

$admin_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin — Cipamilk' ?></title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;600;700&display=swap">
    <style>
        :root {
            --primary: #4DA8DA;
            --primary-dark: #3A8BBD;
            --accent: #F4C95D;
            --green: #4F772D;
        }

        body {
            font-family: 'Nunito', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .brand-text {
            font-family: 'Poppins', sans-serif;
        }

        .main-sidebar {
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%) !important;
        }

        .nav-sidebar .nav-link.active {
            background: var(--primary) !important;
            color: #fff !important;
            border-radius: 8px;
        }

        .nav-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7) !important;
            border-radius: 8px;
            margin: 2px 8px;
        }

        .nav-sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
        }

        .nav-sidebar .nav-icon {
            color: var(--accent) !important;
        }

        .main-header.navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .brand-link {
            background: rgba(255, 255, 255, 0.05) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            padding: 1rem !important;
        }

        .brand-text {
            font-weight: 700 !important;
            color: #fff !important;
        }

        .small-box {
            border-radius: 12px;
            overflow: hidden;
        }

        .small-box .inner h3 {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
        }

        .content-wrapper {
            background: #f4f6f9;
        }

        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-success {
            background: var(--green);
            border-color: var(--green);
        }

        .badge-aktif,
        .badge-success {
            background: rgba(79, 119, 45, 0.15);
            color: var(--green);
        }

        .badge-pending,
        .badge-warning {
            background: rgba(244, 201, 93, 0.2);
            color: #b8860b;
        }

        .badge-dibatalkan,
        .badge-danger {
            background: rgba(231, 76, 60, 0.15);
            color: #E74C3C;
        }

        .badge-selesai {
            background: rgba(79, 119, 45, 0.15);
            color: var(--green);
        }

        .badge-diproses,
        .badge-info {
            background: rgba(77, 168, 218, 0.15);
            color: var(--primary);
        }

        .badge-dikirim {
            background: rgba(77, 168, 218, 0.15);
            color: var(--primary);
        }

        .user-panel .info a {
            color: #fff !important;
            font-weight: 600;
        }

        .table th {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
            </ul>
        </nav>