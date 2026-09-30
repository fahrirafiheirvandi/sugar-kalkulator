<?php
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../login.php');
    exit;
}

$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Sugar Kalkulator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="../assets/admin.css">
</head>
<body>
<div class="admin-wrapper">

    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-cubes-stacked"></i>
            <span>Sugar Kalkulator</span>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="<?= $current === 'dashboard.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>
            <a href="minuman.php" class="<?= $current === 'minuman.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-mug-hot"></i> Kelola Minuman
            </a>
            <a href="rules.php" class="<?= $current === 'rules.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-diagram-project"></i> Kelola Rules Risiko
            </a>
            <a href="konsumsi.php" class="<?= $current === 'konsumsi.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Konsumsi
            </a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <h2><?= $page_title ?? 'Dashboard' ?></h2>
            <div class="topbar-user" id="userDropdown">
                <div class="topbar-user-trigger" onclick="toggleDropdown()">
                    <i class="fa-solid fa-circle-user"></i>
                    <span><?= htmlspecialchars($_SESSION['nama']) ?></span>
                    <i class="fa-solid fa-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-menu" id="dropdownMenu">
                    <a href="akun.php"><i class="fa-solid fa-gear"></i> Pengaturan Akun</a>
                    <a href="../logout.php" onclick="return confirmLogout(event, this.href)"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                </div>
            </div>
        </div>
        <div class="content-area">