<?php
$page_title = 'Home';
require_once 'includes/header.php';

$user_id = $_SESSION['user_id'];

// Ambil data diri user (buat cek riwayat diabetes)
$user_data = $koneksi->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
$kondisi = $user_data['riwayat_diabetes'] ? 'diabetes' : 'normal';

// =========================================================
// HITUNG TOTAL GULA HARI INI
// =========================================================
$stmt = $koneksi->prepare("
    SELECT COALESCE(SUM(m.kadar_gula_gram * k.jumlah_porsi), 0) AS total_gula
    FROM konsumsi k
    JOIN minuman m ON k.minuman_id = m.id
    WHERE k.user_id = ? AND k.tanggal = CURDATE()
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total_gula = $stmt->get_result()->fetch_assoc()['total_gula'];

// =========================================================
// SISTEM PAKAR: COCOKIN TOTAL GULA KE RULE YANG SESUAI
// =========================================================
$stmt = $koneksi->prepare("
    SELECT * FROM rules_risiko
    WHERE kondisi_khusus = ?
    AND is_active = 1
    AND ? >= batas_bawah_gram
    AND (batas_atas_gram IS NULL OR ? <= batas_atas_gram)
    LIMIT 1
");
$stmt->bind_param("sdd", $kondisi, $total_gula, $total_gula);
$stmt->execute();
$rule = $stmt->get_result()->fetch_assoc();

// Warna & icon beda tergantung level risiko
$level = $rule['level_risiko'] ?? 'Aman';
$warna_level = [
    'Aman'     => 'level-aman',
    'Sedang'   => 'level-sedang',
    'Waspada'  => 'level-waspada',
    'Tinggi'   => 'level-tinggi',
    'Bahaya'   => 'level-bahaya',
][$level] ?? 'level-aman';

$icon_level = [
    'Aman'     => 'fa-circle-check',
    'Sedang'   => 'fa-circle-info',
    'Waspada'  => 'fa-triangle-exclamation',
    'Tinggi'   => 'fa-triangle-exclamation',
    'Bahaya'   => 'fa-circle-exclamation',
][$level] ?? 'fa-circle-check';
// Minuman hari ini (buat list ringkas)
$stmt2 = $koneksi->prepare("
    SELECT m.nama_minuman, m.gambar, m.kadar_gula_gram, k.jumlah_porsi
    FROM konsumsi k JOIN minuman m ON k.minuman_id = m.id
    WHERE k.user_id = ? AND k.tanggal = CURDATE()
    ORDER BY k.created_at DESC
");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$minuman_hari_ini = $stmt2->get_result();
?>

<div class="greeting">
    <p>Halo, <strong><?= htmlspecialchars($user_data['nama']) ?></strong> </p>
    <span class="tanggal"><?= date('l, d F Y') ?></span>
</div>

<div class="gula-summary">
    <div class="gula-circle <?= $warna_level ?>">
        <span class="gula-angka"><?= round($total_gula, 1) ?></span>
        <span class="gula-unit">gram</span>
    </div>
    <p class="gula-label">Total Gula Hari Ini</p>
</div>

<?php if ($rule): ?>
<div class="warning-card <?= $warna_level ?>">
    <div class="warning-header">
        <i class="fa-solid <?= $icon_level ?>"></i>
        <span class="level-badge"><?= htmlspecialchars($level) ?></span>
    </div>
    <p class="warning-text"><?= htmlspecialchars($rule['pesan_warning']) ?></p>

    <div class="rekomendasi-box">
        <p><i class="fa-solid fa-person-running"></i> <strong>Olahraga:</strong> <?= htmlspecialchars($rule['rekomendasi_olahraga']) ?></p>
    </div>
    <div class="rekomendasi-box">
        <p><i class="fa-solid fa-utensils"></i> <strong>Pola Makan:</strong> <?= htmlspecialchars($rule['rekomendasi_pola_makan']) ?></p>
    </div>
</div>
<?php endif; ?>

<div class="section-title">
    <h3><i class="fa-solid fa-mug-hot"></i> Minuman Hari Ini</h3>
</div>

<div class="minuman-list">
    <?php if ($minuman_hari_ini->num_rows === 0): ?>
        <p class="empty-state">Belum ada minuman yang di-setor hari ini.</p>
    <?php else: ?>
        <?php while ($m = $minuman_hari_ini->fetch_assoc()): ?>
            <div class="minuman-item">
                <div class="minuman-item-left">
                    <?php if (!empty($m['gambar'])): ?>
                        <img src="../<?= htmlspecialchars($m['gambar']) ?>" class="thumbnail-list" alt="">
                    <?php endif; ?>
                    <div>
                        <p class="minuman-nama"><?= htmlspecialchars($m['nama_minuman']) ?></p>
                        <p class="minuman-porsi"><?= $m['jumlah_porsi'] ?>x porsi</p>
                    </div>
                </div>
                <span class="minuman-gula"><?= $m['kadar_gula_gram'] * $m['jumlah_porsi'] ?>g</span>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>