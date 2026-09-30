<?php
$page_title = 'Pengaturan Akun';
require_once 'includes/header.php';

$error = '';
$success = '';
$admin_id = $_SESSION['user_id'];

// =========================================================
// FORM 1: UPDATE DATA DIRI (nama & username)
// =========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'data_diri') {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);

    $cek = $koneksi->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $cek->bind_param("si", $username, $admin_id);
    $cek->execute();

    if ($cek->get_result()->num_rows > 0) {
        $error = 'Username udah dipake, coba yang lain!';
    } else {
        $stmt = $koneksi->prepare("UPDATE users SET nama=?, username=? WHERE id=?");
        $stmt->bind_param("ssi", $nama, $username, $admin_id);
        $stmt->execute();

        $_SESSION['nama'] = $nama;
        $_SESSION['username'] = $username;

        $success = 'Data diri berhasil diupdate!';
    }
}

// =========================================================
// FORM 2: UBAH PASSWORD
// =========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'ubah_password') {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi = $_POST['konfirmasi_password_baru'];

    $cek_user = $koneksi->query("SELECT password FROM users WHERE id = $admin_id")->fetch_assoc();

    if (!password_verify($password_lama, $cek_user['password'])) {
        $error = 'Password lama salah!';
    } elseif ($password_baru !== $konfirmasi) {
        $error = 'Konfirmasi password baru tidak cocok!';
    } elseif (strlen($password_baru) < 6) {
        $error = 'Password baru minimal 6 karakter!';
    } else {
        $hash_baru = password_hash($password_baru, PASSWORD_BCRYPT);
        $stmt = $koneksi->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hash_baru, $admin_id);
        $stmt->execute();
        $success = 'Password berhasil diubah!';
    }
}

$admin_data = $koneksi->query("SELECT * FROM users WHERE id = $admin_id")->fetch_assoc();
?>

<?php if ($error): ?><div class="alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

<div class="panel">
    <h3><i class="fa-solid fa-id-card"></i> Data Diri</h3>
    <form method="POST" action="akun.php" class="form-grid">
        <input type="hidden" name="form_type" value="data_diri">

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($admin_data['nama']) ?>" required>
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($admin_data['username']) ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<div class="panel">
    <h3><i class="fa-solid fa-lock"></i> Ubah Password</h3>
    <form method="POST" action="akun.php" class="form-grid">
        <input type="hidden" name="form_type" value="ubah_password">

        <div class="form-group">
            <label>Password Lama</label>
            <div class="password-wrapper">
                <input type="password" name="password_lama" id="passLama" required>
                <i class="fa-solid fa-eye" onclick="togglePassAdmin('passLama', this)"></i>
            </div>
        </div>
        <div class="form-group">
            <label>Password Baru</label>
            <div class="password-wrapper">
                <input type="password" name="password_baru" id="passBaru" required minlength="6">
                <i class="fa-solid fa-eye" onclick="togglePassAdmin('passBaru', this)"></i>
            </div>
        </div>
        <div class="form-group">
            <label>Konfirmasi Password Baru</label>
            <div class="password-wrapper">
                <input type="password" name="konfirmasi_password_baru" id="passKonfirmasi" required minlength="6">
                <i class="fa-solid fa-eye" onclick="togglePassAdmin('passKonfirmasi', this)"></i>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-key"></i> Ubah Password
            </button>
        </div>
    </form>
</div>

<script>
    function togglePassAdmin(inputId, icon) {
        const input = document.getElementById(inputId);
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }
</script>

<?php require_once 'includes/footer.php'; ?>