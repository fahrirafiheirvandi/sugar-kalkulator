<?php
$page_title = 'Data Diri';
$back_url = 'profil.php';
require_once 'includes/header.php';

$success = '';
$error = '';
$user_id = $_SESSION['user_id'];
$upload_dir = '../uploads/profil/';

function uploadFotoProfil($file, $upload_dir) {
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        return ['error' => 'Format foto harus jpg, jpeg, png, atau webp!'];
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        return ['error' => 'Ukuran foto maksimal 2MB!'];
    }

    $nama_file = uniqid('profil_') . '.' . $ext;
    $tujuan = $upload_dir . $nama_file;

    if (move_uploaded_file($file['tmp_name'], $tujuan)) {
        return ['success' => 'uploads/profil/' . $nama_file];
    }
    return ['error' => 'Gagal upload foto, coba lagi!'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $berat = $_POST['berat_badan'] ?: NULL;
    $usia = $_POST['usia'] ?: NULL;
    $diabetes = isset($_POST['riwayat_diabetes']) ? 1 : 0;
    $foto_path = $_POST['foto_lama'] ?? NULL;
    // Kalau user centang "Hapus Foto Profil", hapus file fisiknya
    // dan kosongin foto_path biar disimpan NULL ke database.
    if (isset($_POST['hapus_foto']) && !empty($foto_path)) {
        if (file_exists('../' . $foto_path)) {
            unlink('../' . $foto_path);
        }
        $foto_path = NULL;
    }

    $cek = $koneksi->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $cek->bind_param("si", $username, $user_id);
    $cek->execute();

    if ($cek->get_result()->num_rows > 0) {
        $error = 'Username udah dipake orang lain, coba yang lain!';
    } else {
        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['size'] > 0) {
            $hasil_upload = uploadFotoProfil($_FILES['foto_profil'], $upload_dir);
            if (isset($hasil_upload['error'])) {
                $error = $hasil_upload['error'];
            } else {
                if (!empty($_POST['foto_lama']) && file_exists('../' . $_POST['foto_lama'])) {
                    unlink('../' . $_POST['foto_lama']);
                }
                $foto_path = $hasil_upload['success'];
            }
        }

        if (empty($error)) {
            $stmt = $koneksi->prepare("UPDATE users SET nama=?, username=?, berat_badan=?, usia=?, riwayat_diabetes=?, foto_profil=? WHERE id=?");
            $stmt->bind_param("ssiiisi", $nama, $username, $berat, $usia, $diabetes, $foto_path, $user_id);
            $stmt->execute();

            $_SESSION['nama'] = $nama;
            $_SESSION['username'] = $username;

            $success = 'Profil berhasil diupdate!';
        }
    }
}

$user_data = $koneksi->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
?>

<?php if ($error): ?>
    <div class="alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert-success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" action="profil-data.php" enctype="multipart/form-data" class="form-mobile">
    <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($user_data['foto_profil'] ?? '') ?>">

    <div class="form-group">
        <label>Foto Profil</label>
        <input type="file" name="foto_profil" accept=".jpg,.jpeg,.png,.webp">

        <?php if (!empty($user_data['foto_profil'])): ?>
            <div class="form-check-mobile" style="margin-top:8px;">
                <label>
                    <input type="checkbox" name="hapus_foto" value="1">
                    <span>Hapus foto profil saat ini</span>
                </label>
            </div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($user_data['nama']) ?>" required>
    </div>

    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user_data['username']) ?>" required>
    </div>

    <div class="form-group">
        <label>Berat Badan (kg)</label>
        <input type="number" name="berat_badan" value="<?= $user_data['berat_badan'] ?? '' ?>" placeholder="Misal: 60">
    </div>

    <div class="form-group">
        <label>Usia</label>
        <input type="number" name="usia" value="<?= $user_data['usia'] ?? '' ?>" placeholder="Misal: 22">
    </div>

    <div class="form-group form-check-mobile">
        <label>
            <input type="checkbox" name="riwayat_diabetes" <?= $user_data['riwayat_diabetes'] ? 'checked' : '' ?>>
            <span>Punya riwayat diabetes</span>
        </label>
        <p class="hint-text">Kalau dicentang, sistem bakal pake batas gula yang lebih ketat buat kamu.</p>
    </div>

    <button type="submit" class="btn-primary-full">
        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
    </button>
</form>

<?php require_once 'includes/footer.php'; ?>