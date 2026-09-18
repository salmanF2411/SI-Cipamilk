<?php
/**
 * Admin Login Redirect — Cipamilk E-Commerce
 * Halaman login terpusat di /frontend/login.php
 */
require_once __DIR__ . '/../config/database.php';
redirect($base_url . '/frontend/login.php');
