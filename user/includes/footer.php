    </div>

    <nav class="bottom-nav">
        <a href="index.php" class="<?= $current === 'index.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
        <a href="tambah.php" class="<?= $current === 'tambah.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-circle-plus"></i>
            <span>Setor</span>
        </a>
        <a href="riwayat.php" class="<?= $current === 'riwayat.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>Riwayat</span>
        </a>
        <a href="profil.php" class="<?= in_array($current, ['profil.php', 'profil-data.php', 'profil-password.php']) ? 'active' : '' ?>">
            <i class="fa-solid fa-user"></i>
            <span>Profil</span>
        </a>
    </nav>

</div>
<script>
    function confirmDelete(e, url) {
        e.preventDefault();
        Swal.fire({
            title: 'Yakin mau hapus?',
            text: 'Data yang dihapus tidak bisa dikembalikan lagi!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53935',
            cancelButtonColor: '#999',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            width: '300px',
            padding: '1.2em',
            customClass: {
                popup: 'swal-mobile',
                title: 'swal-mobile-title',
                htmlContainer: 'swal-mobile-text',
                confirmButton: 'swal-mobile-btn',
                cancelButton: 'swal-mobile-btn'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
        return false;
    }

    function confirmLogout(e, url) {
        e.preventDefault();
        Swal.fire({
            title: 'Yakin mau logout?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#43a047',
            cancelButtonColor: '#999',
            confirmButtonText: 'Ya, logout',
            cancelButtonText: 'Batal',
            width: '300px',
            padding: '1.2em',
            customClass: {
                popup: 'swal-mobile',
                title: 'swal-mobile-title',
                htmlContainer: 'swal-mobile-text',
                confirmButton: 'swal-mobile-btn',
                cancelButton: 'swal-mobile-btn'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
        return false;
    }
</script>
</body>
</html>