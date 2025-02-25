<div id="editRegionModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeEditRegionModal()">×</button>
        <h2>Edit Region</h2>
        <form id="editRegionForm" method="POST">
            @csrf
            <input type="hidden" id="kode_region-input" name="kode_region">
            <div class="form-container">
                <div class="left-column">
                    <div class="form-group">
                        <label for="namaRegionEdit">Nama Region</label>
                        <input type="text" id="namaRegionEdit" name="nama_region" required>
                    </div>

                    <div class="form-group">
                        <label for="kodeRegionEdit">Kode Region</label>
                        <input type="text" id="kodeRegionEdit" name="kode_region" required>
                    </div>

                    <div class="form-group">
                        <label for="emailEdit">Email</label>
                        <input type="email" id="emailEdit" name="email">
                    </div>
                </div>

                <div class="right-column">
                    <div class="form-group">
                        <label for="alamatEdit">Alamat</label>
                        <input type="text" id="alamatEdit" name="alamat">
                    </div>

                    <div class="form-group">
                        <label for="koordinatEdit">Koordinat</label>
                        <input type="text" id="koordinatEdit" name="koordinat">
                    </div>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="add-button">Update</button>
            </div>
        </form>
    </div>
</div>

<div id="editSiteModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeEditSiteModal()">×</button>
        <h2>Edit Site</h2>
        <form id="editSiteForm" method="POST">
            @csrf
            <input type="hidden" id="kode_site-input" name="kode_site">
            <div class="form-container">
                <div class="left-column">
                    <div class="form-group">
                        <label for="namaSiteEdit">Nama Site</label>
                        <input type="text" id="namaSiteEdit" name="nama_site" required>
                    </div>

                    <div class="form-group">
                        <label for="kodeSiteEdit">Kode Site</label>
                        <input type="text" id="kodeSiteEdit" name="kode_site" required>
                    </div>
                </div>

                <div class="right-column">
                    <div class="form-group">
                        <label for="jenisSiteEdit">Jenis Site</label>
                        <input type="text" id="jenisSiteEdit" name="jenis_site">
                    </div>
                    <div class="form-group">
                        <label for="jmlRackEdit">Jumlah Rack</label>
                        <input type="text" id="jmlRackEdit" name="jml_rack">
                    </div>
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
                if (modal.id === 'editRegionModal') {
                    closeEditRegionModal();
                }
            }
        });
    });

    // Handle form submission for editing region
    $('#editRegionForm').on('submit', function(e) {
        e.preventDefault();
        const id_region = $('#kode_region-input').val();
        closeEditRegionModal();
        
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
                    url: `/update-region/${id_region}`,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil!', 'Data berhasil diupdate.', 'success').then(() => {
                                closeEditRegionModal();
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

    $('#editSiteForm').on('submit', function(e) {
        e.preventDefault();
        const id_site = $('#kode_site-input').val();
        closeEditSiteModal();
        
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
                    url: `/update-site/${id_site}`,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil!', 'Data berhasil diupdate.', 'success').then(() => {
                                closeEditSiteModal();
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

function editRegion(kode_region) {
    $.get(`/get-region/${kode_region}`)
        .done(function(response) {
            if (response.success) {
                const region = response.region;
                $('#kode_region-input').val(region.id_region);
                $('#namaRegionEdit').val(region.nama_region);
                $('#kodeRegionEdit').val(region.kode_region);
                $('#emailEdit').val(region.email);
                $('#alamatEdit').val(region.alamat);
                $('#koordinatEdit').val(region.koordinat);
                document.getElementById("editRegionModal").style.display = "flex";
            } else {
                Swal.fire('Error!', 'Gagal mengambil data region', 'error');
            }
        })
        .fail(function(xhr) {
            console.error('Error:', xhr);
            Swal.fire('Error!', 'Gagal mengambil data region', 'error');
        });
}

function editSite(kode_site) {
    event.stopPropagation(); // Prevent event bubbling
    
    $.ajax({
        url: `/get-site/${kode_site}`,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                const site = response.site;
                // Fix: Change kode_region-input to kode_site-input
                $('#kode_site-input').val(site.id_site);
                $('#namaSiteEdit').val(site.nama_site);
                $('#kodeSiteEdit').val(site.kode_site);
                $('#jenisSiteEdit').val(site.jenis_site);
                $('#jmlRackEdit').val(site.jml_rack);
                document.getElementById("editSiteModal").style.display = "flex";
            } else {
                Swal.fire('Error!', response.message || 'Gagal mengambil data site', 'error');
            }
        },
        error: function(xhr) {
            console.error('Ajax Error:', xhr);
            Swal.fire('Error!', 'Gagal mengambil data site', 'error');
        }
    });
}

function closeEditRegionModal() {
    document.getElementById("editRegionModal").style.display = "none";
}

function closeEditSiteModal() {
    document.getElementById("editSiteModal").style.display = "none";
}
</script>