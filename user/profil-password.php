<?php
$page_title = 'Ubah Password';
$back_url = 'profil.php';
require_once 'includes/header.php';

$success = '';
$error = '';
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi = $_POST['konfirmasi_password_baru'];

    $cek_user = $koneksi->query("SELECT password FROM users WHERE id = $user_id")->fetch_assoc();

    if (!password_verify($password_lama, $cek_user['password'])) {
        $error = 'Password lama salah!';
    } elseif ($password_baru !== $konfirmasi) {
        $error = 'Konfirmasi password baru tidak cocok!';
    } elseif (strlen($password_baru) < 6) {
        $error = 'Password baru minimal 6 karakter!';
    } else {
        $hash_baru = password_hash($password_baru, PASSWORD_BCRYPT);
        $stmt = $koneksi->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hash_baru, $user_id);
        $stmt->execute();
        $success = 'Password berhasil diubah!';
    }
}
?>

<?php if ($error): ?>
    <div class="alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert-success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" action="profil-password.php" class="form-mobile">
    <div class="form-group">
        <label>Password Lama</label>
        <div class="password-wrapper-mobile">
            <input type="password" name="password_lama" id="passLama" required>
            <i class="fa-solid fa-eye" onclick="togglePass('passLama', this)"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Password Baru</label>
        <div class="password-wrapper-mobile">
            <input type="password" name="password_baru" id="passBaru" required minlength="6">
            <i class="fa-solid fa-eye" onclick="togglePass('passBaru', this)"></i>
        </div>
    </div>

    <div class="form-group">
        <label>Konfirmasi Password Baru</label>
        <div class="password-wrapper-mobile">
            <input type="password" name="konfirmasi_password_baru" id="passKonfirmasi" required minlength="6">
            <i class="fa-solid fa-eye" onclick="togglePass('passKonfirmasi', this)"></i>
        </div>
    </div>

    <button type="submit" class="btn-primary-full">
        <i class="fa-solid fa-key"></i> Ubah Password
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