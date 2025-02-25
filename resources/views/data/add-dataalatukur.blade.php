<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="addBrandModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeAddBrandModal()">×</button>
        <h2>Tambah Brand Alatukur Baru</h2>
        <form id="addBrandForm" method="POST">
            @csrf
            <div class="form-container">
                <div class="form-group">
                    <label for="nama_brand">Nama Brand</label>
                    <input type="text" id="nama_brand" name="nama_brand" required>
                </div>
                <div class="form-group">
                    <label for="kode_brand">Kode Brand</label>
                    <input type="text" id="kode_brand" name="kode_brand" required>
                </div>
            </div>
            <div class="button-container">
                <button type="submit" class="add-button-data">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="addJenisModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeAddJenisModal()">×</button>
        <h2>Tambah Jenis Alatukur Baru</h2>
        <form id="addJenisForm" method="POST">
            @csrf
            <div class="form-container">
                <div class="form-group">
                    <label for="nama_alatukur">Nama Alatukur</label>
                    <input type="text" id="nama_alatukur" name="nama_alatukur" required>
                </div>
                <div class="form-group">
                    <label for="kode_alatukur">Kode Alatukur</label>
                    <input type="text" id="kode_alatukur" name="kode_alatukur" required>
                </div>
            </div>
            <div class="button-container">
                <button type="submit" class="add-button-data">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#addBrandForm').on('submit', function(e) {
        e.preventDefault();
        closeAddBrandModal();

        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin menambahkan data ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f52ba',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: '/store-brand-alatukur',
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data berhasil disimpan.',
                                icon: 'success',
                                confirmButtonColor: '#4f52ba',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#addBrandForm')[0].reset(); // Reset form
                                loadData(); // Memuat ulang data tanpa refresh
                                closeAddBrandModal(); // Tutup modal
                            });
                        } else {
                            Swal.fire('Error!', response.message || 'Terjadi kesalahan', 'error');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire('Error!', errorMessage, 'error');
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).text('Simpan');
                    }
                });
            }
        });
    });
});

function openAddBrandModal() {
    $('#addBrandForm')[0].reset();
    document.getElementById("addBrandModal").style.display = "flex";
}

function closeAddBrandModal() {
    document.getElementById("addBrandModal").style.display = "none";
}

document.addEventListener('DOMContentLoaded', function() {
    $('#addJenisForm').on('submit', function(e) {
        e.preventDefault();
        closeAddJenisModal();

        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin menambahkan data ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f52ba',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: '/store-jenis-alatukur',
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data berhasil disimpan.',
                                icon: 'success',
                                confirmButtonColor: '#4f52ba',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#addJenisForm')[0].reset(); // Reset form
                                loadData(); // Memuat ulang data tanpa refresh
                                closeAddJenisModal(); // Tutup modal
                            });
                        } else {
                            Swal.fire('Error!', response.message || 'Terjadi kesalahan', 'error');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire('Error!', errorMessage, 'error');
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).text('Simpan');
                    }
                });
            }
        });
    });
});

function openAddJenisModal() {
    $('#addJenisForm')[0].reset();
    document.getElementById("addJenisModal").style.display = "flex";
}

function closeAddJenisModal() {
    document.getElementById("addJenisModal").style.display = "none";
}
</script>