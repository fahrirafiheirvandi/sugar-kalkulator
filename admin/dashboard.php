<?php
$page_title = 'Dashboard';
require_once 'includes/header.php';

// =========================================================
// STATISTIK RINGKAS
// =========================================================
$total_user = $koneksi->query("SELECT COUNT(*) AS total FROM users WHERE role = 'user'")->fetch_assoc()['total'];
$total_minuman = $koneksi->query("SELECT COUNT(*) AS total FROM minuman")->fetch_assoc()['total'];
$konsumsi_hari_ini = $koneksi->query("SELECT COUNT(*) AS total FROM konsumsi WHERE tanggal = CURDATE()")->fetch_assoc()['total'];

$rata_gula = $koneksi->query("
    SELECT AVG(total_gula) AS rata FROM (
        SELECT SUM(m.kadar_gula_gram * k.jumlah_porsi) AS total_gula
        FROM konsumsi k
        JOIN minuman m ON k.minuman_id = m.id
        WHERE k.tanggal = CURDATE()
        GROUP BY k.user_id
    ) AS sub
")->fetch_assoc()['rata'];
$rata_gula = $rata_gula ? round($rata_gula, 1) : 0;

// 5 konsumsi terbaru
$recent = $koneksi->query("
    SELECT u.nama, m.nama_minuman, m.kadar_gula_gram, k.jumlah_porsi, k.tanggal, k.created_at
    FROM konsumsi k
    JOIN users u ON k.user_id = u.id
    JOIN minuman m ON k.minuman_id = m.id
    ORDER BY k.created_at DESC
    LIMIT 5
");
?>

<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-icon" style="background:#e8f5e9;color:#43a047;"><i class="fa-solid fa-users"></i></div>
        <div><h3><?= $total_user ?></h3><p>User Terdaftar</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e0f7fa;color:#00b4d8;"><i class="fa-solid fa-mug-hot"></i></div>
        <div><h3><?= $total_minuman ?></h3><p>Jenis Minuman</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff3e0;color:#ff9800;"><i class="fa-solid fa-clipboard-check"></i></div>
        <div><h3><?= $konsumsi_hari_ini ?></h3><p>Setoran Hari Ini</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fce4ec;color:#d6336c;"><i class="fa-solid fa-cube"></i></div>
        <div><h3><?= $rata_gula ?>g</h3><p>Rata² Gula/User Hari Ini</p></div>
    </div>
</div>

<div class="panel">
    <h3><i class="fa-solid fa-clock-rotate-left"></i> Setoran Terbaru</h3>
    <table class="data-table">
        <thead>
            <tr><th>User</th><th>Minuman</th><th>Gula</th><th>Porsi</th><th>Tanggal</th></tr>
        </thead>
        <tbody>
            <?php if ($recent->num_rows === 0): ?>
                <tr><td colspan="5" class="empty-row">Belum ada data konsumsi</td></tr>
            <?php else: ?>
                <?php while ($row = $recent->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['nama_minuman']) ?></td>
                        <td><?= $row['kadar_gula_gram'] ?>g</td>
                        <td><?= $row['jumlah_porsi'] ?>x</td>
                        <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>