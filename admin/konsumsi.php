<?php
$page_title = 'Riwayat Konsumsi';
require_once 'includes/header.php';

if (isset($_GET['hapus'])) {
    $stmt = $koneksi->prepare("DELETE FROM konsumsi WHERE id = ?");
    $stmt->bind_param("i", $_GET['hapus']);
    $stmt->execute();
    header('Location: konsumsi.php');
    exit;
}

$data = $koneksi->query("
    SELECT k.id, u.nama, m.nama_minuman, m.kadar_gula_gram, k.jumlah_porsi, k.tanggal, k.created_at
    FROM konsumsi k
    JOIN users u ON k.user_id = u.id
    JOIN minuman m ON k.minuman_id = m.id
    ORDER BY k.created_at DESC
");
?>

<div class="panel">
    <h3><i class="fa-solid fa-clock-rotate-left"></i> Semua Riwayat Konsumsi (<?= $data->num_rows ?>)</h3>
    <div class="table-scroll">
        <table class="data-table">
            <thead>
                <tr><th>User</th><th>Minuman</th><th>Gula/Porsi</th><th>Jumlah Porsi</th><th>Total Gula</th><th>Tanggal</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($data->num_rows === 0): ?>
                    <tr><td colspan="7" class="empty-row">Belum ada data konsumsi</td></tr>
                <?php else: ?>
                    <?php while ($row = $data->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['nama_minuman']) ?></td>
                            <td><?= $row['kadar_gula_gram'] ?>g</td>
                            <td><?= $row['jumlah_porsi'] ?>x</td>
                            <td><strong><?= $row['kadar_gula_gram'] * $row['jumlah_porsi'] ?>g</strong></td>
                            <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                            <td class="action-cell">
                                <a href="konsumsi.php?hapus=<?= $row['id'] ?>" class="btn-icon btn-icon-danger" title="Hapus"
                                onclick="return confirmDelete(event, this.href)"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>