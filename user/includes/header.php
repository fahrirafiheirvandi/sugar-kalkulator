<?php
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ../../login.php');
    exit;
}

$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Sugar Kalkulator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="../assets/user.css">
</head>
<body>
<div class="phone-frame">

        <div class="app-topbar">
        <div class="topbar-left">
            <?php if (!empty($back_url)): ?>
                <a href="<?= $back_url ?>" class="icon-back"><i class="fa-solid fa-arrow-left"></i></a>
            <?php endif; ?>
            <h2><?= $page_title ?? 'Sugar Kalkulator' ?></h2>
        </div>
        <a href="../logout.php" class="icon-logout" onclick="return confirmLogout(event, this.href)">
    <i class="fa-solid fa-right-from-bracket"></i>
</a>
    </div>

    <div class="app-content">