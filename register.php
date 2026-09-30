<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi_password'];

    if (empty($nama) || empty($username) || empty($password)) {
        $error = 'Semua field wajib diisi!';
    } elseif ($password !== $konfirmasi) {
        $error = 'Konfirmasi password tidak cocok!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } else {
        $cek = $koneksi->prepare("SELECT id FROM users WHERE username = ?");
        $cek->bind_param("s", $username);
        $cek->execute();
        if ($cek->get_result()->num_rows > 0) {
            $error = 'Username udah dipake, coba yang lain!';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $koneksi->prepare("INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->bind_param("sss", $nama, $username, $hash);

            if ($stmt->execute()) {
                $success = 'Registrasi berhasil! Silakan login.';
            } else {
                $error = 'Gagal daftar, coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Sugar Kalkulator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1><i class="fa-solid fa-user-plus"></i> Daftar Akun</h1>
                <p>Mulai pantau konsumsi gula harianmu</p>
            </div>

            <?php if ($error): ?>
                <div class="alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert-success"><?= htmlspecialchars($success) ?> <a href="login.php">Login sekarang</a></div>
            <?php endif; ?>

            <?php if (!$success): ?>
            <form method="POST" action="register.php">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" placeholder="Nama kamu" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username buat login" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required minlength="6">
                        <i class="fa-solid fa-eye" id="togglePassword"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="konfirmasi_password">Konfirmasi Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="konfirmasi_password" name="konfirmasi_password" placeholder="Ulangi password" required minlength="6">
                        <i class="fa-solid fa-eye" id="toggleKonfirmasi"></i>
                    </div>
                </div>

                <button type="submit" class="btn-login">Daftar</button>
            </form>
            <?php endif; ?>

            <p class="register-link">Udah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>

    <script>
        function setupTogglePassword(iconId, inputId) {
            const icon = document.getElementById(iconId);
            const input = document.getElementById(inputId);
            if (!icon || !input) return;
            icon.addEventListener('click', function () {
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }
        setupTogglePassword('togglePassword', 'password');
        setupTogglePassword('toggleKonfirmasi', 'konfirmasi_password');
    </script>
</body>
</html>