<?php
/**
 * Proses Logout — Cipamilk E-Commerce
 */
require_once __DIR__ . '/../../config/database.php';

session_destroy();
header("Location: $base_url/frontend/index.php");
exit;
