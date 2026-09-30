<?php
$page_title = 'Setor Minuman';
require_once 'includes/header.php';

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $minuman_id = $_POST['minuman_id'];
    $jumlah_porsi = $_POST['jumlah_porsi'];
    $tanggal = $_POST['tanggal'];

    $stmt = $koneksi->prepare("INSERT INTO konsumsi (user_id, minuman_id, jumlah_porsi, tanggal) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $_SESSION['user_id'], $minuman_id, $jumlah_porsi, $tanggal);
    $stmt->execute();

    $success = 'Berhasil disetor! Cek halaman Home buat lihat totalnya.';
}

$minuman_list = $koneksi->query("SELECT * FROM minuman ORDER BY kategori, nama_minuman");
?>

<?php if ($success): ?>
    <div class="alert-success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="section-title">
    <h3><i class="fa-solid fa-mug-hot"></i> Pilih Minuman</h3>
</div>

<div class="search-bar-mobile">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" id="searchMinumanUser" placeholder="Cari minuman atau merek...">
</div>

<form method="POST" action="tambah.php" id="formSetor">
    <input type="hidden" name="minuman_id" id="selectedMinumanId" value="">

    <div class="minuman-grid" id="gridMinuman">
        <?php while ($m = $minuman_list->fetch_assoc()): ?>
            <?php
                $gula = (float) $m['kadar_gula_gram'];
                $sendok = round($gula / 12, 1);
                $cari_text = strtolower($m['nama_minuman'] . ' ' . $m['merek'] . ' ' . $m['kategori']);
                $gambar_url = $m['gambar'] ? '../' . htmlspecialchars($m['gambar']) : '';
            ?>
            <div class="minuman-card" data-cari="<?= htmlspecialchars($cari_text) ?>">
                <button type="button" class="info-btn"
                    onclick="event.stopPropagation(); showInfo(this)"
                    data-nama="<?= htmlspecialchars($m['nama_minuman']) ?>"
                    data-merek="<?= htmlspecialchars($m['merek'] ?? '-') ?>"
                    data-gambar="<?= $gambar_url ?>"
                    data-deskripsi="<?= htmlspecialchars($m['deskripsi'] ?? 'Belum ada deskripsi.') ?>"
                    data-gula="<?= $gula ?>"
                    data-sendok="<?= $sendok ?>"
                    data-kalori="<?= $m['kalori'] ?? '-' ?>"
                    data-ukuran="<?= htmlspecialchars($m['ukuran_saji']) ?>">
                    <i class="fa-solid fa-circle-info"></i>
                </button>

                <div class="minuman-card-inner"
                    onclick="openOrderModal(this)"
                    data-id="<?= $m['id'] ?>"
                    data-nama="<?= htmlspecialchars($m['nama_minuman']) ?>"
                    data-gambar="<?= $gambar_url ?>"
                    data-gula="<?= $gula ?>">
                    <?php if ($m['gambar']): ?>
                        <img src="<?= $gambar_url ?>" alt="<?= htmlspecialchars($m['nama_minuman']) ?>">
                    <?php else: ?>
                        <div class="no-image"><i class="fa-solid fa-mug-hot"></i></div>
                    <?php endif; ?>
                    <p class="mc-nama"><?= htmlspecialchars($m['nama_minuman']) ?></p>
                    <p class="mc-merek"><?= htmlspecialchars($m['merek'] ?? '-') ?></p>
                    <span class="mc-gula"><?= $gula ?>g gula</span>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- BOTTOM SHEET: MUNCUL PAS KARTU MINUMAN DIKLIK -->
    <div class="sheet-overlay" id="orderModalOverlay" onclick="closeOrderModal(event)">
        <div class="sheet-box form-mobile">
            <div class="sheet-handle"></div>

            <div class="sheet-header">
                <img id="orderModalGambar" src="" alt="" class="sheet-gambar">
                <div>
                    <h3 id="orderModalNama"></h3>
                    <span id="orderModalGula" class="sheet-gula-badge"></span>
                </div>
            </div>

            <div class="form-group">
                <label>Jumlah Porsi</label>
                <input type="number" name="jumlah_porsi" id="orderJumlahPorsi" value="1" min="1" required>
            </div>

            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" id="orderTanggal" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="sheet-actions">
                <button type="button" class="btn-cancel-order" onclick="closeOrderModal()">Batal</button>
                <button type="submit" class="btn-primary-full">
                    <i class="fa-solid fa-paper-plane"></i> Setor Minuman
                </button>
            </div>
        </div>
    </div>
</form>

<!-- MODAL POPUP DETAIL MINUMAN (info) -->
<div class="modal-overlay" id="modalOverlay" onclick="closeInfo(event)">
    <div class="modal-box">
        <button type="button" class="modal-close" onclick="closeInfo()"><i class="fa-solid fa-xmark"></i></button>
        <img id="modalGambar" src="" alt="" class="modal-gambar">
        <h3 id="modalNama"></h3>
        <p id="modalMerek" class="modal-merek"></p>

        <div class="modal-highlight">
            <i class="fa-solid fa-cube"></i>
            <div>
                <p class="modal-gula-besar"><span id="modalGula"></span>g Gula</p>
                <p class="modal-sendok">≈ <span id="modalSendok"></span> sendok makan gula pasir</p>
            </div>
        </div>

        <div class="modal-info-grid">
            <div><span>Ukuran Saji</span><strong id="modalUkuran"></strong></div>
            <div><span>Kalori</span><strong id="modalKalori"></strong></div>
        </div>

        <p class="modal-section-title">Deskripsi / Ingredients</p>
        <p class="modal-deskripsi" id="modalDeskripsi"></p>
    </div>
</div>

<script>
    document.getElementById('searchMinumanUser').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#gridMinuman .minuman-card').forEach(function (card) {
            card.style.display = card.dataset.cari.includes(q) ? '' : 'none';
        });
    });

    // ===== BOTTOM SHEET SETOR MINUMAN =====
    function openOrderModal(el) {
        document.getElementById('orderModalNama').textContent = el.dataset.nama;
        document.getElementById('orderModalGula').textContent = el.dataset.gula + 'g gula';
        document.getElementById('selectedMinumanId').value = el.dataset.id;

        const gambarEl = document.getElementById('orderModalGambar');
        if (el.dataset.gambar) {
            gambarEl.src = el.dataset.gambar;
            gambarEl.style.display = 'block';
        } else {
            gambarEl.style.display = 'none';
        }

        document.getElementById('orderModalOverlay').classList.add('show');
    }

    function closeOrderModal(e) {
        if (e && e.target !== document.getElementById('orderModalOverlay') && !e.target.closest('.btn-cancel-order')) return;
        document.getElementById('orderModalOverlay').classList.remove('show');
        document.getElementById('selectedMinumanId').value = '';
    }

    // ===== MODAL INFO MINUMAN =====
    function showInfo(btn) {
        document.getElementById('modalNama').textContent = btn.dataset.nama;
        document.getElementById('modalMerek').textContent = btn.dataset.merek;
        document.getElementById('modalGula').textContent = btn.dataset.gula;
        document.getElementById('modalSendok').textContent = btn.dataset.sendok;
        document.getElementById('modalUkuran').textContent = btn.dataset.ukuran;
        document.getElementById('modalKalori').textContent = btn.dataset.kalori + ' kkal';
        document.getElementById('modalDeskripsi').textContent = btn.dataset.deskripsi;

        const gambarEl = document.getElementById('modalGambar');
        if (btn.dataset.gambar) {
            gambarEl.src = btn.dataset.gambar;
            gambarEl.style.display = 'block';
        } else {
            gambarEl.style.display = 'none';
        }

        document.getElementById('modalOverlay').classList.add('show');
    }

    function closeInfo(e) {
        if (e && e.target !== document.getElementById('modalOverlay') && !e.target.closest('.modal-close')) return;
        document.getElementById('modalOverlay').classList.remove('show');
    }
</script>

<?php require_once 'includes/footer.php'; ?>