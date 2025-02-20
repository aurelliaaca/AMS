<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="addPerangkatModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeAddPerangkatModal()">×</button>
        <h2>Tambah Perangkat Baru</h2>
        <form id="addPerangkatForm" method="POST">
            @csrf
            <div class="form-container">
                <div class="left-column">
                    <div class="form-group">
                        <label for="regionAdd">Region</label>
                        <select id="regionAdd" name="kode_region" required>
                            <option value="">Pilih Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="siteAdd">Site</label>
                        <select id="siteAdd" name="kode_site" required disabled>
                            <option value="">Pilih Site</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="perangkatAdd">Perangkat</label>
                        <select id="perangkatAdd" name="kode_perangkat" required>
                            <option value="">Pilih Perangkat</option>
                            @foreach($listpkt as $p)
                                <option value="{{ $p->kode_perangkat }}">{{ $p->nama_perangkat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="brandAdd">Brand</label>
                        <select id="brandAdd" name="kode_brand">
                            <option value="">Pilih Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->kode_brand }}">{{ $brand->nama_brand }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="type">Type</label>
                        <input type="text" id="type" name="type">
                    </div>
                </div>

                <div class="right-column">
                    <div class="form-group">
                        <label for="no_rack">No Rack</label>
                        <select id="no_rack" name="no_rack" class="form-control">
                            <option value="">Pilih No Rack</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="uawal">U Awal</label>
                        <input type="text" id="uawal" name="uawal">
                    </div>

                    <div class="form-group">
                        <label for="uakhir">U Akhir</label>
                        <input type="text" id="uakhir" name="uakhir">
                    </div>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="add-button">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle region change
    $('#regionAdd').on('change', function() {
        var regionId = $(this).val();
        var siteSelect = $('#siteAdd');
        
        // Reset site dropdown
        siteSelect.html('<option value="">Pilih Site</option>');
        
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

    // Handle site change for rack
    $('#siteAdd').on('change', function() {
        var siteId = $(this).val();
        var rackSelect = $('#no_rack');
        
        rackSelect.html('<option value="">Pilih No Rack</option>');
        
        if (siteId) {
            rackSelect.prop('disabled', false);
            
            $.ajax({
                url: '/get-site-rack',
                type: 'GET',
                data: { site: siteId },
                success: function(response) {
                    if (response.jml_rack > 0) {
                        $('#no_rack').prop('disabled', false);
                        // Generate options from 1 to jml_rack
                        for (let i = 1; i <= response.jml_rack; i++) {
                            $('#no_rack').append(`<option value="${i}">Rack ${i}</option>`);
                        }
                    } else {
                        $('#no_rack').append('<option value="">Tidak ada rack tersedia</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching racks:', error);
                    $('#no_rack').append('<option value="">Error loading racks</option>');
                }
            });
        } else {
            $('#no_rack').prop('disabled', true);
        }
    });

    // Handle form submission for adding perangkat
    $('#addPerangkatForm').on('submit', function(e) {
        e.preventDefault();
        closeAddPerangkatModal(); // Ini akan menutup modal sebelum SweetAlert muncul

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
                // Disable the submit button to prevent multiple submissions
                const submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true).text('Menyimpan...'); // Change button text

                $.ajax({
                    url: '/store-perangkat',
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message before closing the modal
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data berhasil disimpan.',
                                icon: 'success',
                                confirmButtonColor: '#4f52ba',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                closeAddPerangkatModal(); // Close the modal after the alert is confirmed
                                LoadData(); // Reload the data
                            });
                        } else {
                            Swal.fire('Error!', response.message || 'Terjadi kesalahan', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        if (xhr.status === 400) {
                            // Jika terjadi overlap, tampilkan pesan error dengan teks baru
                            Swal.fire({
                                title: 'Error!',
                                text: `Di antara U${$('#uawal').val()}-U${$('#uakhir').val()} di Rack ${$('#no_rack').val()} ${$('#siteAdd option:selected').text()}, ${$('#regionAdd option:selected').text()} sudah terisi.`,
                                icon: 'error',
                                confirmButtonColor: '#4f52ba',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                openAddPerangkatModal(); // Buka kembali modal setelah alert
                            });
                        } else {
                            Swal.fire('Error!', 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                        }
                    },
                    complete: function() {
                        // Re-enable the submit button and reset text
                        submitButton.prop('disabled', false).text('Simpan');
                    }
                });
            }
        });
    });

    const modalOverlay = document.getElementById('addPerangkatModal');

    modalOverlay.addEventListener('click', function(event) {
        if (event.target === modalOverlay) {
            closeAddPerangkatModal(); // Menutup modal
        }
    });
});

function openAddPerangkatModal() {
    // Reset form
    $('#addPerangkatForm')[0].reset();
    
    // Reset and disable dependent dropdowns
    $('#siteAdd').html('<option value="">Pilih Site</option>').prop('disabled', true);
    $('#no_rack').html('<option value="">Pilih No Rack</option>').prop('disabled', true);
    
    // Show modal
    document.getElementById("addPerangkatModal").style.display = "flex";
}

function closeAddPerangkatModal() {
    document.getElementById("addPerangkatModal").style.display = "none";
}

function showSwal(type, message) {
    if (type === 'success') {
        swal({
            title: "Berhasil!",
            text: message,
            type: "success",
            button: {
                text: "OK",
                value: true,
                visible: true,
                className: "btn btn-primary"
            }
        });
    } else if (type === 'error') {
        swal({
            title: "Error!",
            text: message,
            type: "error",
            button: {
                text: "OK",
                value: true,
                visible: true,
                className: "btn btn-danger"
            }
        });
    } else if (type === 'confirm-delete') {
        swal({
            title: "Apakah Anda yakin?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#4f52ba",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal",
            closeOnConfirm: false
        }, function(isConfirm) {
            if (isConfirm) {
                // Lanjutkan dengan penghapusan
                return true;
            }
        });
    }
}
</script>