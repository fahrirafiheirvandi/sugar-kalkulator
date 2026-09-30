<?php
$page_title = 'Hapus Akun';
$back_url = 'profil.php';
require_once 'includes/header.php';

$error = '';
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    $cek_user = $koneksi->query("SELECT password FROM users WHERE id = $user_id")->fetch_assoc();

    if (!$cek_user || !password_verify($password, $cek_user['password'])) {
        $error = 'Password salah! Akun tidak dihapus.';
    } else {
        // Hapus dulu semua riwayat konsumsi user (foreign key),
        // baru hapus akunnya biar ga error.
        $stmt = $koneksi->prepare("DELETE FROM konsumsi WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Hapus juga file foto profil kalau ada, biar ga jadi sampah di server.
        $user_data = $koneksi->query("SELECT foto_profil FROM users WHERE id = $user_id")->fetch_assoc();
        if (!empty($user_data['foto_profil']) && file_exists('../' . $user_data['foto_profil'])) {
            unlink('../' . $user_data['foto_profil']);
        }

        $stmt = $koneksi->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        session_destroy();
        header('Location: ../login.php?akun_dihapus=1');
        exit;
    }
}
?>

<?php if ($error): ?>
    <div class="alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="alert-error" style="background:#fff3e0; color:#e65100;">
    <i class="fa-solid fa-circle-exclamation"></i>
    Tindakan ini permanen! Semua data profil dan riwayat konsumsi gula kamu bakal dihapus dan tidak bisa dikembalikan lagi.
</div>

<form method="POST" action="profil-hapus.php" class="form-mobile" onsubmit="return confirm('Yakin mau hapus akun? Tindakan ini tidak bisa dibatalkan!');">
    <div class="form-group">
        <label>Masukkan Password Kamu untuk Konfirmasi</label>
        <div class="password-wrapper-mobile">
            <input type="password" name="password" id="passHapus" required>
            <i class="fa-solid fa-eye" onclick="togglePass('passHapus', this)"></i>
        </div>
    </div>

    <button type="submit" class="btn-primary-full" style="background:#e53935;">
        <i class="fa-solid fa-trash"></i> Hapus Akun Saya
    </button>
</form>

<script>
    function togglePass(inputId, icon) {
        const input = document.getElementById(inputId);
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }
</script>

<?php require_once 'includes/footer.php'; ?>