<?php
$page_title = 'Profil';
require_once 'includes/header.php';

$user_id = $_SESSION['user_id'];
$user_data = $koneksi->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
?>

<div class="profil-avatar">
    <?php if (!empty($user_data['foto_profil'])): ?>
        <img src="../<?= htmlspecialchars($user_data['foto_profil']) ?>" class="foto-profil-besar" alt="Foto Profil">
    <?php else: ?>
        <i class="fa-solid fa-circle-user"></i>
    <?php endif; ?>
    <h3><?= htmlspecialchars($user_data['nama']) ?></h3>
    <p>@<?= htmlspecialchars($user_data['username']) ?></p>
</div>

<div class="settings-list">
    <a href="profil-data.php" class="settings-item">
        <div class="settings-item-left">
            <i class="fa-solid fa-id-card"></i>
            <span>Data Diri</span>
        </div>
        <i class="fa-solid fa-chevron-right"></i>
    </a>

    <a href="profil-password.php" class="settings-item">
        <div class="settings-item-left">
            <i class="fa-solid fa-lock"></i>
            <span>Ubah Password</span>
        </div>
        <i class="fa-solid fa-chevron-right"></i>
    </a>

    <a href="profil-hapus.php" class="settings-item">
        <div class="settings-item-left">
            <i class="fa-solid fa-trash" style="color:#e53935;"></i>
            <span style="color:#e53935;">Hapus Akun</span>
        </div>
        <i class="fa-solid fa-chevron-right"></i>
    </a>
</div>

<?php require_once 'includes/footer.php'; ?>