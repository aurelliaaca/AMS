<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="addSiteModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeAddSiteModal()">×</button>
        <h2>Tambah Site Baru</h2>
        <form id="addSiteForm" method="POST">
            @csrf
            <input type="hidden" id="id_region" name="id_region">
            <input type="hidden" id="kode_region" name="kode_region">
            <div class="form-container">
                <div class="left-column">
                    <div class="form-group">
                        <label for="nama_site">Nama Site</label>
                        <input type="text" id="nama_site" name="nama_site" required>
                    </div>
                    <div class="form-group">
                        <label for="kode_site">Kode Site</label>
                        <input type="text" id="kode_site" name="kode_site" required>
                    </div>
                </div>
                <div class="right-column">
                    <div class="form-group">
                        <label for="jenis_site">Jenis Site</label>
                        <input type="text" id="jenis_site" name="jenis_site">
                    </div>
                    <div class="form-group">
                        <label for="jml_rack">Jumlah Rack</label>
                        <input type="text" id="jml_rack" name="jml_rack">
                    </div>
                </div>
            </div>
            <div class="button-container">
                <button type="submit" class="add-button-data">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="addRegionModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeAddRegionModal()">×</button>
        <h2>Tambah Region Baru</h2>
        <form id="addRegionForm" method="POST">
            @csrf
            <div class="form-container">
                <div class="left-column">
                    <div class="form-group">
                        <label for="nama_region">Nama Region</label>
                        <input type="text" id="nama_region" name="nama_region" required>
                    </div>
                    <div class="form-group">
                        <label for="kode_region">Kode Region</label>
                        <input type="text" id="kode_region" name="kode_region" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email">
                    </div>
                </div>
                <div class="right-column">
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" id="alamat" name="alamat">
                    </div>
                    <div class="form-group">
                        <label for="koordinat">Koordinat</label>
                        <input type="text" id="koordinat" name="koordinat">
                    </div>
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
    $('#addSiteForm').on('submit', function(e) {
        e.preventDefault();
        closeAddSiteModal();

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
                    url: '/store-site',
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
                                $('#addSiteForm')[0].reset(); // Reset form
                                loadData(); // Memuat ulang data tanpa refresh
                                closeAddSiteModal(); // Tutup modal
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

function openAddSiteModal(regionId) {
    $('#addSiteForm')[0].reset();
    $('#id_region').val(regionId);
    
    // Ambil data region untuk mengisi kode_region
    $.ajax({
        url: `/get-region/${regionId}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#kode_region').val(response.region.kode_region);
                document.getElementById("addSiteModal").style.display = "flex";
            } else {
                Swal.fire('Error!', 'Gagal mengambil data region', 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Error!', 'Gagal mengambil data region', 'error');
        }
    });
}

function closeAddSiteModal() {
    document.getElementById("addSiteModal").style.display = "none";
}

document.addEventListener('DOMContentLoaded', function() {
    $('#addRegionForm').on('submit', function(e) {
        e.preventDefault();
        closeAddRegionModal();

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
                    url: '/store-region',
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
                                $('#addRegionForm')[0].reset(); // Reset form
                                loadData(); 
                                closeAddRegionModal(); // Tutup modal
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

function openAddRegionModal() {
    $('#addRegionForm')[0].reset();
    document.getElementById("addRegionModal").style.display = "flex";
}

function closeAddRegionModal() {
    document.getElementById("addRegionModal").style.display = "none";
}
</script>
