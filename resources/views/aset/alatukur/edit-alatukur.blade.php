<div id="editAlatukurModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeEditAlatukurModal()">×</button>
        <h2>Edit Alatukur</h2>
        <form id="editAlatukurForm" method="POST">
            @csrf
            <input type="hidden" id="id_alatukur-input" name="id_alatukur">
            <div class="form-container">
                <div class="left-column">
                    <div class="form-group">
                        <label for="regionEdit">Region</label>
                        <select id="regionEdit" name="kode_region" required>
                            <option value="">Pilih Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="alatukurEdit">Alatukur</label>
                        <select id="alatukurEdit" name="kode_alatukur" required>
                            <option value="">Pilih Alatukur</option>
                            @foreach($listpkt as $p)
                                <option value="{{ $p->kode_alatukur }}">{{ $p->nama_alatukur }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="brandEdit">Brand</label>
                        <select id="brandEdit" name="kode_brand">
                            <option value="">Pilih Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->kode_brand }}">{{ $brand->nama_brand }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="typeEdit">Type</label>
                        <input type="text" id="typeEdit" name="type">
                    </div>

                    <div class="form-group">
                        <label for="serialnumberEdit">Serial Number</label>
                        <input type="text" id="serialnumberEdit" name="serialnumber">
                    </div>
                </div>

                <div class="right-column">
                    <div class="form-group">
                    <label for="tahunperolehanEdit">Tahun Perolehan</label>
                    <select id="tahunperolehanEdit" name="tahunperolehan" class="form-control">
                        <option value="">Pilih Tahun</option>
                    </select>
                    </div>
                    <div class="form-group">
                        <label for="kondisiEdit">Kondisi</label>
                        <input type="text" id="kondisiEdit" name="kondisi">
                    </div>
                    <div class="form-group">
                        <label for="keteranganEdit">Keterangan</label>
                        <input type="text" id="keteranganEdit" name="keterangan">
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
    const selectTahun = document.getElementById('tahunperolehanEdit');
    const tahunSekarang = new Date().getFullYear();
    for (let i = tahunSekarang; i >= 2000; i--) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = i;
        selectTahun.appendChild(option);
    }
    
    // Handle region change for edit
    $('#regionEdit').on('change', function() {
        var regionId = $(this).val();
        var siteSelect = $('#siteEdit');
        // Reset site dropdown
        siteSelect.html('<option value="">Pilih Site</option>');
        
        if (regionId) {
            // Handle additional functionality if necessary
        }
    });

    // Handle form submission for editing alatukur
    $('#editAlatukurForm').on('submit', function(e) {
        e.preventDefault();
        const id_alatukur = $('#id_alatukur-input').val();
        closeEditAlatukurModal(); // Close modal before SweetAlert appears
        
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
                // Disable the submit button to prevent multiple submissions
                const submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true).text('Mengupdate...'); // Change button text

                $.ajax({
                    url: `/update-alatukur/${id_alatukur}`,
                    type: 'PUT',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message before closing the modal
                            Swal.fire('Berhasil!', 'Data berhasil diupdate.', 'success').then(() => {
                                closeEditAlatukurModal(); // Close the modal after the alert is confirmed
                                LoadData(); // Reload the data
                            });
                        } else {
                            Swal.fire('Error!', response.message || 'Terjadi kesalahan', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        Swal.fire('Error!', 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                    },
                    complete: function() {
                        // Re-enable the submit button and reset text
                        submitButton.prop('disabled', false).text('Update');
                    }
                });
            }
        });
    });
});

function editAlatukur(id_alatukur) {
    $.get(`/get-alatukur/${id_alatukur}`, function(response) {
        if (response.success) {
            const alatukur = response.alatukur;
            console.log(alatukur); // Cek data yang diterima
            $('#id_alatukur-input').val(alatukur.id_alatukur);
            $('#alatukurEdit').val(alatukur.kode_alatukur);
            $('#brandEdit').val(alatukur.kode_brand);
            $('#typeEdit').val(alatukur.type);
            $('#serialnumberEdit').val(alatukur.serialnumber);
            $('#tahunperolehanEdit').val(alatukur.tahunperolehan);
            $('#keteranganEdit').val(alatukur.keterangan);
            $('#kondisiEdit').val(alatukur.kondisi);
            $('#regionEdit').val(alatukur.kode_region);
            
            // Show modal
            document.getElementById("editAlatukurModal").style.display = "flex";
        }
    });
}


function closeEditAlatukurModal() {
    document.getElementById("editAlatukurModal").style.display = "none";
}
</script>