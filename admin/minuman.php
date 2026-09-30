<?php
$page_title = 'Kelola Minuman';
require_once 'includes/header.php';

$error = '';
$success = '';
$upload_dir = '../uploads/minuman/';

function uploadGambar($file, $upload_dir) {
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        return ['error' => 'Format gambar harus jpg, jpeg, png, atau webp!'];
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        return ['error' => 'Ukuran gambar maksimal 2MB!'];
    }

    $nama_file = uniqid('minuman_') . '.' . $ext;
    $tujuan = $upload_dir . $nama_file;

    if (move_uploaded_file($file['tmp_name'], $tujuan)) {
        return ['success' => 'uploads/minuman/' . $nama_file];
    }
    return ['error' => 'Gagal upload gambar, coba lagi!'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_minuman']);
    $merek = trim($_POST['merek']);
    $kategori = trim($_POST['kategori']);
    $deskripsi = trim($_POST['deskripsi']);
    $ukuran = trim($_POST['ukuran_saji']);
    $gula = $_POST['kadar_gula_gram'];
    $kalori = $_POST['kalori'] ?: NULL;
    $gambar_path = $_POST['gambar_lama'] ?? NULL;

    if (isset($_FILES['gambar']) && $_FILES['gambar']['size'] > 0) {
        $hasil_upload = uploadGambar($_FILES['gambar'], $upload_dir);
        if (isset($hasil_upload['error'])) {
            $error = $hasil_upload['error'];
        } else {
            if (!empty($_POST['gambar_lama']) && file_exists('../' . $_POST['gambar_lama'])) {
                unlink('../' . $_POST['gambar_lama']);
            }
            $gambar_path = $hasil_upload['success'];
        }
    }

    if (empty($error)) {
        if (!empty($_POST['minuman_id'])) {
            $stmt = $koneksi->prepare("UPDATE minuman SET nama_minuman=?, merek=?, kategori=?, deskripsi=?, ukuran_saji=?, kadar_gula_gram=?, kalori=?, gambar=? WHERE id=?");
            $stmt->bind_param("sssssdisi", $nama, $merek, $kategori, $deskripsi, $ukuran, $gula, $kalori, $gambar_path, $_POST['minuman_id']);
            $success = 'Minuman berhasil diupdate!';
        } else {
            $stmt = $koneksi->prepare("INSERT INTO minuman (nama_minuman, merek, kategori, deskripsi, ukuran_saji, kadar_gula_gram, kalori, gambar) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssdis", $nama, $merek, $kategori, $deskripsi, $ukuran, $gula, $kalori, $gambar_path);
            $success = 'Minuman baru berhasil ditambahkan!';
        }
        $stmt->execute();
    }
}

if (isset($_GET['hapus'])) {
    $cek = $koneksi->prepare("SELECT COUNT(*) AS total FROM konsumsi WHERE minuman_id = ?");
    $cek->bind_param("i", $_GET['hapus']);
    $cek->execute();
    $dipake = $cek->get_result()->fetch_assoc()['total'];

    if ($dipake > 0) {
        $error = 'Minuman ini masih ada di histori konsumsi (' . $dipake . ' data), nggak bisa dihapus.';
    } else {
        $get_gambar = $koneksi->prepare("SELECT gambar FROM minuman WHERE id = ?");
        $get_gambar->bind_param("i", $_GET['hapus']);
        $get_gambar->execute();
        $g = $get_gambar->get_result()->fetch_assoc();
        if ($g && $g['gambar'] && file_exists('../' . $g['gambar'])) {
            unlink('../' . $g['gambar']);
        }
        $stmt = $koneksi->prepare("DELETE FROM minuman WHERE id = ?");
        $stmt->bind_param("i", $_GET['hapus']);
        $stmt->execute();
        header('Location: minuman.php');
        exit;
    }
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $stmt = $koneksi->prepare("SELECT * FROM minuman WHERE id = ?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $edit_data = $stmt->get_result()->fetch_assoc();
}

$list = $koneksi->query("SELECT * FROM minuman ORDER BY nama_minuman");
?>

<?php if ($error): ?><div class="alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

<div class="panel">
    <h3><i class="fa-solid fa-<?= $edit_data ? 'pen' : 'plus' ?>"></i> <?= $edit_data ? 'Edit' : 'Tambah' ?> Minuman</h3>
    <form method="POST" action="minuman.php" class="form-grid" enctype="multipart/form-data">
        <?php if ($edit_data): ?>
            <input type="hidden" name="minuman_id" value="<?= $edit_data['id'] ?>">
            <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($edit_data['gambar'] ?? '') ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Nama Minuman</label>
            <input type="text" name="nama_minuman" placeholder="Misal: Thai Tea" required
                   value="<?= $edit_data ? htmlspecialchars($edit_data['nama_minuman']) : '' ?>">
        </div>
        <div class="form-group">
            <label>Merek</label>
            <input type="text" name="merek" placeholder="Misal: Chatime, Ultra Milk, dll" required
                   value="<?= $edit_data ? htmlspecialchars($edit_data['merek'] ?? '') : '' ?>">
        </div>
        <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" placeholder="Misal: Teh, Susu, Boba" required
                   value="<?= $edit_data ? htmlspecialchars($edit_data['kategori']) : '' ?>">
        </div>
        <div class="form-group">
            <label>Ukuran Saji</label>
            <input type="text" name="ukuran_saji" placeholder="Misal: 250 ml" required
                   value="<?= $edit_data ? htmlspecialchars($edit_data['ukuran_saji']) : '' ?>">
        </div>
        <div class="form-group">
            <label>Kadar Gula (gram)</label>
            <input type="number" step="0.1" name="kadar_gula_gram" placeholder="Misal: 20.0" required
                   value="<?= $edit_data ? $edit_data['kadar_gula_gram'] : '' ?>">
        </div>
        <div class="form-group">
            <label>Kalori (opsional)</label>
            <input type="number" name="kalori" placeholder="Misal: 90"
                   value="<?= $edit_data ? $edit_data['kalori'] : '' ?>">
        </div>

        <div class="form-group form-group-wide">
            <label>Deskripsi / Ingredients</label>
            <textarea name="deskripsi" rows="3" placeholder="Misal: Teh hitam berkualitas dengan gula alami, tanpa pengawet..."><?= $edit_data ? htmlspecialchars($edit_data['deskripsi'] ?? '') : '' ?></textarea>
        </div>

        <div class="form-group form-group-wide">
            <label>Foto Minuman <?= $edit_data && $edit_data['gambar'] ? '(kosongin kalau nggak mau ganti)' : '' ?></label>
            <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp" <?= $edit_data ? '' : 'required' ?>>
            <?php if ($edit_data && $edit_data['gambar']): ?>
                <img src="../<?= htmlspecialchars($edit_data['gambar']) ?>" class="preview-gambar-lama" alt="Preview">
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-<?= $edit_data ? 'floppy-disk' : 'plus' ?>"></i> <?= $edit_data ? 'Update' : 'Simpan' ?>
            </button>
            <?php if ($edit_data): ?><a href="minuman.php" class="btn-secondary"><i class="fa-solid fa-xmark"></i> Batal</a><?php endif; ?>
        </div>
    </form>
</div>

<div class="panel">
    <h3><i class="fa-solid fa-list"></i> Daftar Minuman (<?= $list->num_rows ?>)</h3>

    <div class="search-bar">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="searchMinuman" placeholder="Cari nama, merek, atau kategori...">
    </div>

    <div class="table-scroll">
        <table class="data-table" id="tabelMinuman">
            <thead>
                <tr><th>Foto</th><th>Nama</th><th>Merek</th><th>Kategori</th><th>Ukuran</th><th>Gula</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($list->num_rows === 0): ?>
                    <tr><td colspan="7" class="empty-row">Belum ada minuman</td></tr>
                <?php else: ?>
                    <?php while ($m = $list->fetch_assoc()): ?>
                        <tr class="baris-cari" data-cari="<?= strtolower(htmlspecialchars($m['nama_minuman'] . ' ' . $m['merek'] . ' ' . $m['kategori'])) ?>">
                            <td>
                                <?php if ($m['gambar']): ?>
                                    <img src="../<?= htmlspecialchars($m['gambar']) ?>" class="thumbnail-tabel" alt="">
                                <?php else: ?>
                                    <span class="badge badge-gray">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($m['nama_minuman']) ?></td>
                            <td><?= htmlspecialchars($m['merek'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($m['kategori']) ?></td>
                            <td><?= htmlspecialchars($m['ukuran_saji']) ?></td>
                            <td><strong><?= $m['kadar_gula_gram'] ?>g</strong></td>
                            <td class="action-cell">
                                <a href="minuman.php?edit=<?= $m['id'] ?>" class="btn-icon" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <a href="minuman.php?hapus=<?= $m['id'] ?>" class="btn-icon btn-icon-danger" title="Hapus"
                                onclick="return confirmDelete(event, this.href)"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('searchMinuman').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#tabelMinuman .baris-cari').forEach(function (row) {
            row.style.display = row.dataset.cari.includes(q) ? '' : 'none';
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>