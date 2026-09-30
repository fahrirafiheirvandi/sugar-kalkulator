<?php
$page_title = 'Kelola Rules Risiko';
require_once 'includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batas_bawah = $_POST['batas_bawah_gram'];
    $batas_atas = $_POST['batas_atas_gram'] === '' ? NULL : $_POST['batas_atas_gram'];
    $kondisi = $_POST['kondisi_khusus'];
    $level = trim($_POST['level_risiko']);
    $pesan = trim($_POST['pesan_warning']);
    $olahraga = trim($_POST['rekomendasi_olahraga']);
    $pola_makan = trim($_POST['rekomendasi_pola_makan']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($_POST['rule_id'])) {
        $stmt = $koneksi->prepare("UPDATE rules_risiko SET batas_bawah_gram=?, batas_atas_gram=?, kondisi_khusus=?, level_risiko=?, pesan_warning=?, rekomendasi_olahraga=?, rekomendasi_pola_makan=?, is_active=? WHERE id=?");
        $stmt->bind_param("ddssssii", $batas_bawah, $batas_atas, $kondisi, $level, $pesan, $olahraga, $pola_makan, $is_active, $_POST['rule_id']);
        $success = 'Rule berhasil diupdate!';
    } else {
        $stmt = $koneksi->prepare("INSERT INTO rules_risiko (batas_bawah_gram, batas_atas_gram, kondisi_khusus, level_risiko, pesan_warning, rekomendasi_olahraga, rekomendasi_pola_makan, is_active) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ddssssi", $batas_bawah, $batas_atas, $kondisi, $level, $pesan, $olahraga, $pola_makan, $is_active);
        $success = 'Rule baru berhasil ditambahkan!';
    }
    $stmt->execute();
}

if (isset($_GET['hapus'])) {
    $stmt = $koneksi->prepare("DELETE FROM rules_risiko WHERE id=?");
    $stmt->bind_param("i", $_GET['hapus']);
    $stmt->execute();
    header('Location: rules.php');
    exit;
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $stmt = $koneksi->prepare("SELECT * FROM rules_risiko WHERE id = ?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $edit_data = $stmt->get_result()->fetch_assoc();
}

$rules = $koneksi->query("SELECT * FROM rules_risiko ORDER BY kondisi_khusus, batas_bawah_gram");
?>

<?php if ($error): ?><div class="alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

<div class="panel">
    <h3><i class="fa-solid fa-<?= $edit_data ? 'pen' : 'plus' ?>"></i> <?= $edit_data ? 'Edit Rule' : 'Tambah Rule Baru' ?></h3>
    <form method="POST" action="rules.php" class="form-grid">
        <?php if ($edit_data): ?><input type="hidden" name="rule_id" value="<?= $edit_data['id'] ?>"><?php endif; ?>

        <div class="form-group">
            <label>Batas Bawah (gram)</label>
            <input type="number" step="0.1" name="batas_bawah_gram" required
                   value="<?= $edit_data ? $edit_data['batas_bawah_gram'] : '' ?>">
        </div>
        <div class="form-group">
            <label>Batas Atas (kosongin = tanpa batas)</label>
            <input type="number" step="0.1" name="batas_atas_gram"
                   value="<?= $edit_data ? $edit_data['batas_atas_gram'] : '' ?>">
        </div>
        <div class="form-group">
            <label>Kondisi Khusus</label>
            <select name="kondisi_khusus" required>
                <option value="normal" <?= ($edit_data && $edit_data['kondisi_khusus'] === 'normal') ? 'selected' : '' ?>>Normal</option>
                <option value="diabetes" <?= ($edit_data && $edit_data['kondisi_khusus'] === 'diabetes') ? 'selected' : '' ?>>Riwayat Diabetes</option>
            </select>
        </div>
        <div class="form-group">
            <label>Level Risiko</label>
            <input type="text" name="level_risiko" placeholder="Aman / Waspada / Bahaya" required
                   value="<?= $edit_data ? htmlspecialchars($edit_data['level_risiko']) : '' ?>">
        </div>

        <div class="form-group form-group-wide">
            <label>Pesan Warning</label>
            <textarea name="pesan_warning" rows="2" required><?= $edit_data ? htmlspecialchars($edit_data['pesan_warning']) : '' ?></textarea>
        </div>
        <div class="form-group form-group-wide">
            <label>Rekomendasi Olahraga</label>
            <textarea name="rekomendasi_olahraga" rows="2" required><?= $edit_data ? htmlspecialchars($edit_data['rekomendasi_olahraga']) : '' ?></textarea>
        </div>
        <div class="form-group form-group-wide">
            <label>Rekomendasi Pola Makan</label>
            <textarea name="rekomendasi_pola_makan" rows="2" required><?= $edit_data ? htmlspecialchars($edit_data['rekomendasi_pola_makan']) : '' ?></textarea>
        </div>

        <div class="form-group form-check">
            <label><input type="checkbox" name="is_active" <?= (!$edit_data || $edit_data['is_active']) ? 'checked' : '' ?>> Rule Aktif</label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-<?= $edit_data ? 'floppy-disk' : 'plus' ?>"></i> <?= $edit_data ? 'Update' : 'Simpan' ?>
            </button>
            <?php if ($edit_data): ?><a href="rules.php" class="btn-secondary"><i class="fa-solid fa-xmark"></i> Batal</a><?php endif; ?>
        </div>
    </form>
</div>

<div class="panel">
    <h3><i class="fa-solid fa-list"></i> Daftar Rules (<?= $rules->num_rows ?>)</h3>

    <div class="search-bar">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="searchRules" placeholder="Cari level risiko atau kondisi...">
    </div>

    <div class="table-scroll">
        <table class="data-table" id="tabelRules">
            <thead>
                <tr><th>Range Gula</th><th>Kondisi</th><th>Level</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($rules->num_rows === 0): ?>
                    <tr><td colspan="5" class="empty-row">Belum ada rule</td></tr>
                <?php else: ?>
                    <?php while ($r = $rules->fetch_assoc()): ?>
                        <tr class="baris-cari" data-cari="<?= strtolower(htmlspecialchars($r['level_risiko'] . ' ' . $r['kondisi_khusus'])) ?>">
                            <td><?= $r['batas_bawah_gram'] ?>g - <?= $r['batas_atas_gram'] ?? '∞' ?>g</td>
                            <td><?= $r['kondisi_khusus'] === 'diabetes' ? 'Riwayat Diabetes' : 'Normal' ?></td>
                            <td><strong><?= htmlspecialchars($r['level_risiko']) ?></strong></td>
                            <td>
                                <?php if ($r['is_active']): ?><span class="badge badge-green">Aktif</span>
                                <?php else: ?><span class="badge badge-gray">Nonaktif</span><?php endif; ?>
                            </td>
                            <td class="action-cell">
                                <a href="rules.php?edit=<?= $r['id'] ?>" class="btn-icon" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <a href="rules.php?hapus=<?= $r['id'] ?>" class="btn-icon btn-icon-danger" title="Hapus"
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
    document.getElementById('searchRules').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#tabelRules .baris-cari').forEach(function (row) {
            row.style.display = row.dataset.cari.includes(q) ? '' : 'none';
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>