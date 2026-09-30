<?php
$page_title = 'Riwayat Konsumsi';
require_once 'includes/header.php';

if (isset($_GET['hapus'])) {
    $stmt = $koneksi->prepare("DELETE FROM konsumsi WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $_GET['hapus'], $_SESSION['user_id']);
    $stmt->execute();
    header('Location: riwayat.php');
    exit;
}

$stmt = $koneksi->prepare("
    SELECT k.id, m.nama_minuman, m.kadar_gula_gram, k.jumlah_porsi, k.tanggal
    FROM konsumsi k
    JOIN minuman m ON k.minuman_id = m.id
    WHERE k.user_id = ?
    ORDER BY k.tanggal DESC, k.created_at DESC
    LIMIT 30
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$riwayat = $stmt->get_result();

// Kelompokin per tanggal biar keliatan rapi
$grouped = [];
while ($r = $riwayat->fetch_assoc()) {
    $grouped[$r['tanggal']][] = $r;
}
?>

<div class="section-title">
    <h3><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Konsumsi</h3>
</div>

<?php if (empty($grouped)): ?>
    <p class="empty-state">Belum ada riwayat konsumsi.</p>
<?php else: ?>
    <?php foreach ($grouped as $tanggal => $items): ?>
        <p class="riwayat-tanggal"><?= date('l, d F Y', strtotime($tanggal)) ?></p>
        <div class="minuman-list">
            <?php
            $total_hari = 0;
            foreach ($items as $item):
                $total_hari += $item['kadar_gula_gram'] * $item['jumlah_porsi'];
            ?>
                <div class="minuman-item">
                    <div>
                        <p class="minuman-nama"><?= htmlspecialchars($item['nama_minuman']) ?></p>
                        <p class="minuman-porsi"><?= $item['jumlah_porsi'] ?>x porsi</p>
                    </div>
                    <div class="riwayat-actions">
                        <span class="minuman-gula"><?= $item['kadar_gula_gram'] * $item['jumlah_porsi'] ?>g</span>
                        <a href="riwayat.php?hapus=<?= $item['id'] ?>" class="btn-hapus-kecil"
                         onclick="return confirmDelete(event, this.href)"><i class="fa-solid fa-trash"></i></a>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="minuman-item total-row">
                <p><strong>Total Hari Ini</strong></p>
                <span class="minuman-gula"><strong><?= $total_hari ?>g</strong></span>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>