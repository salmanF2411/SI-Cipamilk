<?php
/**
 * Redirect login admin ke proses login terpadu
 */
require_once __DIR__ . '/../../config/database.php';
redirect($base_url . '/frontend/login.php');
