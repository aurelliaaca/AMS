<div id="editPerangkatModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeEditPerangkatModal()">×</button>
        <h2>Edit Perangkat</h2>
        <form id="editPerangkatForm" method="POST">
            @csrf
            <input type="hidden" id="id_perangkat-input" name="id_perangkat">
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
                        <label for="siteEdit">Site</label>
                        <select id="siteEdit" name="kode_site" required>
                            <option value="">Pilih Site</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="perangkatEdit">Perangkat</label>
                        <select id="perangkatEdit" name="kode_pkt" required>
                            <option value="">Pilih Perangkat</option>
                            @foreach($listpkt as $p)
                                <option value="{{ $p->kode_pkt }}">{{ $p->nama_pkt }}</option>
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
                </div>

                <div class="right-column">
                    <div class="form-group">
                        <label for="no_rackEdit">No Rack</label>
                        <select id="no_rackEdit" name="no_rack" class="form-control">
                            <option value="">Pilih No Rack</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="uawalEdit">U Awal</label>
                        <input type="text" id="uawalEdit" name="uawal">
                    </div>

                    <div class="form-group">
                        <label for="uakhirEdit">U Akhir</label>
                        <input type="text" id="uakhirEdit" name="uakhir">
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
    // Handle region change for edit
    $('#regionEdit').on('change', function() {
        var regionId = $(this).val();
        var siteSelect = $('#siteEdit');
        // Reset site dropdown
        siteSelect.html('<option value="">Pilih Site</option>');
        $('#no_rackEdit').html('<option value="">Pilih No Rack</option>').prop('disabled', true);
        
        if (regionId) {
            siteSelect.prop('disabled', false);
            // Fetch sites based on selected region
            $.ajax({
                url: '/get-sites',
                method: 'GET',
                data: { regions: regionId },
                success: function(response) {
                    if (Object.keys(response).length > 0) {
                        $.each(response, function(kode_site, nama_site) {
                            siteSelect.append(new Option(nama_site, kode_site));
                        });
                    } else {
                        siteSelect.append(new Option('Tidak ada site tersedia', ''));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    siteSelect.append(new Option('Error loading sites', ''));
                }
            });
        } else {
            siteSelect.prop('disabled', true);
        }
    });

    // Handle site change for rack in edit mode
    $('#siteEdit').on('change', function() {
        var siteId = $(this).val();
        var rackSelect = $('#no_rackEdit');
        rackSelect.html('<option value="">Pilih No Rack</option>');
        
        if (siteId) {
            rackSelect.prop('disabled', false);
            $.ajax({
                url: '/get-site-rack',
                type: 'GET',
                data: { site: siteId },
                success: function(response) {
                    if (response.jml_rack > 0) {
                        rackSelect.prop('disabled', false);
                        // Generate options from 1 to jml_rack
                        for (let i = 1; i <= response.jml_rack; i++) {
                            rackSelect.append(`<option value="${i}">Rack ${i}</option>`);
                        }
                    } else {
                        rackSelect.append('<option value="">Tidak ada rack tersedia</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching racks:', error);
                    rackSelect.append('<option value="">Error loading racks</option>');
                }
            });
        } else {
            rackSelect.prop('disabled', true);
        }
    });

    // Handle form submission for editing perangkat
    $('#editPerangkatForm').on('submit', function(e) {
        e.preventDefault();
        const id_perangkat = $('#id_perangkat-input').val();
        closeEditPerangkatModal(); // Ini akan menutup modal sebelum SweetAlert muncul
        
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
                    url: `/update-perangkat/${id_perangkat}`,
                    type: 'PUT',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message before closing the modal
                            Swal.fire('Berhasil!', 'Data berhasil diupdate.', 'success').then(() => {
                                closeEditPerangkatModal(); // Close the modal after the alert is confirmed
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

function editPerangkat(id_perangkat) {
    $.get(`/get-perangkat/${id_perangkat}`, function(response) {
        if (response.success) {
            const perangkat = response.perangkat;
            
            // Set initial form values
            $('#id_perangkat-input').val(perangkat.id_perangkat);
            $('#perangkatEdit').val(perangkat.kode_pkt);
            $('#brandEdit').val(perangkat.kode_brand);
            $('#typeEdit').val(perangkat.type);
            $('#uawalEdit').val(perangkat.uawal);
            $('#uakhirEdit').val(perangkat.uakhir);
            
            // Set region and load dependent data
            $('#regionEdit').val(perangkat.kode_region);
            
            // Load site data based on region
            if (perangkat.kode_region) {
                $.ajax({
                    url: '/get-sites',
                    method: 'GET',
                    data: { regions: perangkat.kode_region },
                    success: function(sites) {
                        $('#siteEdit').empty().prop('disabled', false);
                        $('#siteEdit').append('<option value="">Pilih Site</option>');
                        $.each(sites, function(key, value) {
                            $('#siteEdit').append(new Option(value, key, false, key == perangkat.kode_site));
                        });
                        
                        // Set the selected site
                        $('#siteEdit').val(perangkat.kode_site);
                        
                        // Load rack data if site is selected
                        if (perangkat.kode_site) {
                            $.ajax({
                                url: '/get-site-rack',
                                type: 'GET',
                                data: { site: perangkat.kode_site },
                                success: function(response) {
                                    $('#no_rackEdit').empty().prop('disabled', false);
                                    $('#no_rackEdit').append('<option value="">Pilih No Rack</option>');
                                    
                                    if (response.jml_rack > 0) {
                                        for (let i = 1; i <= response.jml_rack; i++) {
                                            $('#no_rackEdit').append(`<option value="${i}">Rack ${i}</option>`);
                                        }
                                        $('#no_rackEdit').val(perangkat.no_rack);
                                    }
                                }
                            });
                        }
                    }
                });
            }
            
            // Show modal
            document.getElementById("editPerangkatModal").style.display = "flex";
        }
    });
}

function closeEditPerangkatModal() {
    document.getElementById("editPerangkatModal").style.display = "none";
}
</script>