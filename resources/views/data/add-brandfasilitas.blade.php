<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="addBrandModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeAddBrandModal()">×</button>
        <h2>Tambah Brand Fasilitas Baru</h2>
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
        <h2>Tambah Jenis Fasilitas Baru</h2>
        <form id="addJenisForm" method="POST">
            @csrf
            <div class="form-container">
                <div class="form-group">
                    <label for="nama_fasilitas">Nama Fasilitas</label>
                    <input type="text" id="nama_fasilitas" name="nama_fasilitas" required>
                </div>
                <div class="form-group">
                    <label for="kode_fasilitas">Kode Fasilitas</label>
                    <input type="text" id="kode_fasilitas" name="kode_fasilitas" required>
                </div>
            </div>
            <div class="button-container">
                <button type="submit" class="add-button-data">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="editBrandModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeEditBrandModal()">×</button>
        <h2>Edit Brand Fasilitas</h2>
        <form id="editBrandForm" method="POST">
            @csrf
            <input type="hidden" id="edit_kode_brand" name="kode_brand">
            <div class="form-container">
                <div class="form-group">
                    <label for="edit_nama_brand">Nama Brand</label>
                    <input type="text" id="edit_nama_brand" name="nama_brand" required>
                </div>
            </div>
            <div class="button-container">
                <button type="submit" class="add-button-data">Simpan Perubahan</button>
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
                    url: '/store-brand',
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
                    url: '/store-jenis',
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

document.addEventListener('DOMContentLoaded', function() {
    $(document).on('click', '.edit-brand-btn', function() {
        let kodeBrand = $(this).data('kode');

        $.ajax({
            url: `/get-brand/${kodeBrand}`, // Corrected syntax for URL
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#edit_kode_brand').val(response.brand.kode_brand);
                    $('#edit_nama_brand').val(response.brand.nama_brand);
                    openEditBrandModal();
                } else {
                    Swal.fire('Error!', response.message || 'Gagal mengambil data brand.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Gagal mengambil data brand.', 'error');
            }
        });
    });

    $('#editBrandForm').on('submit', function(e) {
        e.preventDefault();
        closeEditBrandModal();

        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin menyimpan perubahan?",
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
                    url: '/update-brand/' + $('#edit_kode_brand').val(),
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data berhasil diperbarui.',
                                icon: 'success',
                                confirmButtonColor: '#4f52ba',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $('#editBrandForm')[0].reset();
                                loadData();
                                closeEditBrandModal();
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
                        submitButton.prop('disabled', false).text('Simpan Perubahan');
                    }
                });
            }
        });
    });
});


function openEditBrandModal() {
    document.getElementById("editBrandModal").style.display = "flex";
}

function closeEditBrandModal() {
    document.getElementById("editBrandModal").style.display = "none";
}
</script>