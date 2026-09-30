        </div>
    </main>
</div>

<script>
    function toggleDropdown() {
        document.getElementById('dropdownMenu').classList.toggle('show');
    }
    document.addEventListener('click', function (e) {
        if (!document.getElementById('userDropdown').contains(e.target)) {
            document.getElementById('dropdownMenu').classList.remove('show');
        }
    });

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
            cancelButtonText: 'Batal'
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
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
        return false;
    }
</script>

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
            cancelButtonText: 'Batal'
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
            cancelButtonText: 'Batal'
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