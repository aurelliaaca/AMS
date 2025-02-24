<div id="editBrandModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeEditBrandModal()">×</button>
        <h2>Edit Brand</h2>
        <form id="editBrandForm" method="POST">
            @csrf
            <input type="hidden" id="kode_brand-input" name="kode_brand">
            <div class="form-container">
                    <div class="form-group">
                        <label for="namaBrandEdit">Nama Brand</label>
                        <input type="text" id="namaBrandEdit" name="nama_brand">
                    </div>

                    <div class="form-group">
                        <label for="kodeBrandEdit">Kode Brand</label>
                        <input type="text" id="kodeBrandEdit" name="kode_brand">
                    </div>
            </div>

            <div class="button-container">
                <button type="submit" class="add-button">Update</button>
            </div>
        </form>
    </div>
</div>

<div id="editJenisModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeEditJenisModal()">×</button>
        <h2>Edit Jenis Perangkat</h2>
        <form id="editJenisForm" method="POST">
            @csrf
            <input type="hidden" id="kode_perangkat-input" name="kode_perangkat">
            <div class="form-container">
                <div class="form-group">
                    <label for="namaJenisEdit">Nama Perangkat</label>
                    <input type="text" id="namaJenisEdit" name="nama_perangkat">
                </div>

                <div class="form-group">
                    <label for="kodePerangkatEdit">Kode Perangkat</label>
                    <input type="text" id="kodePerangkatEdit" name="kode_perangkat">
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="add-button">Update</button>
            </div>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Consolidated modal click handlers
    const modals = document.querySelectorAll('.modal-overlay');
    modals.forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                if (modal.id === 'editBrandModal') {
                    closeEditBrandModal();
                } else if (modal.id === 'editJenisModal') {
                    closeEditJenisModal();
                }
            }
        });
    });

    // Handle form submission for editing brand
    $('#editBrandForm').on('submit', function(e) {
        e.preventDefault();
        const kode_brand = $('#kode_brand-input').val();
        closeEditBrandModal();
        
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin mengupdate data ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f52ba',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, update!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true).text('Mengupdate...');

                $.ajax({
                    url: `/update-brand-perangkat/${kode_brand}`,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil!', 'Data berhasil diupdate.', 'success').then(() => {
                                closeEditJenisModal();
                                loadData();
                            });
                        } else {
                            Swal.fire('Error!', response.message || 'Terjadi kesalahan', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).text('Update');
                    }
                });
            }
        });
    });

    // Handle form submission for editing jenis
    $('#editJenisForm').on('submit', function(e) {
        e.preventDefault();
        const kode_perangkat = $('#kode_perangkat-input').val();
        closeEditJenisModal();
        
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin mengupdate data ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f52ba',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, update!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true).text('Mengupdate...');

                $.ajax({
                    url: `/update-jenis-perangkat/${kode_perangkat}`,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil!', 'Data berhasil diupdate.', 'success').then(() => {
                                closeEditJenisModal();
                                loadData();
                            });
                        } else {
                            Swal.fire('Error!', response.message || 'Terjadi kesalahan', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).text('Update');
                    }
                });
            }
        });
    });
});

function editBrand(kode_brand) {
    $.get(`/get-brand-perangkat/${kode_brand}`)
        .done(function(response) {
            if (response.success) {
                const brand = response.brand;
                $('#kode_brand-input').val(brand.kode_brand);
                $('#namaBrandEdit').val(brand.nama_brand);
                $('#kodeBrandEdit').val(brand.kode_brand);
                document.getElementById("editBrandModal").style.display = "flex";
            } else {
                Swal.fire('Error!', 'Gagal mengambil data brand', 'error');
            }
        })
        .fail(function(xhr) {
            console.error('Error:', xhr);
            Swal.fire('Error!', 'Gagal mengambil data brand', 'error');
        });
}

function editJenis(kode_perangkat) {
    $.get(`/get-jenis-perangkat/${kode_perangkat}`)
        .done(function(response) {
            if (response.success) {
                const jenis = response.jenis;
                $('#kode_perangkat-input').val(jenis.kode_perangkat);
                $('#namaJenisEdit').val(jenis.nama_perangkat);
                $('#kodePerangkatEdit').val(jenis.kode_perangkat);
                document.getElementById("editJenisModal").style.display = "flex";
            } else {
                Swal.fire('Error!', 'Gagal mengambil data perangkat', 'error');
            }
        })
        .fail(function(xhr) {
            console.error('Error:', xhr);
            Swal.fire('Error!', 'Gagal mengambil data perangkat', 'error');
        });
}

function closeEditBrandModal() {
    document.getElementById("editBrandModal").style.display = "none";
}

function closeEditJenisModal() {
    document.getElementById("editJenisModal").style.display = "none";
}
</script>