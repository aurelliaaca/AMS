@extends('layouts.sidebar')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Inter", sans-serif;
            font-size: 12px;
        }
        
        .container {
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .dropdown-container {
            display: flex;
            gap: 20px;
            align-items: center;
            width: 100%;
            
        }
        
        .dropdown-container > * {
            flex: 1;
        }
        
        select, .search-bar input {
            width: 100%;
            font-size: 12px;
            padding: 12px 12px;
            border: 1px solid #4f52ba;
            border-radius: 5px;
            background-color: #fff;
            transition: border-color 0.3s;
        }
        
        .search-bar input {
            outline: none;
        }
        
        select:focus, .search-bar input:focus {
            border-color: #4f52ba;
            box-shadow: 0 0 5px rgba(79, 82, 186, 0.5);
        }
        
        .table-container {
            width: 100%;
            overflow-x: auto;
            border-radius: 8px;
        }
        
        
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }
        
        th {
            background-color: #4f52ba;
            color: #fff;
            padding: 12px;
            text-align: center;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
            font-weight: normal;
        }
        
        .no-data {
            text-align: center;
            color: rgba(79, 82, 186, 0.2);
        }
        
        .select2-container {
            width: 100%;
        }
        
        .select2-selection {
            width: 100%;
            font-size: 12px;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            transition: border-color 0.3s;
            border: 1px solid #4f52ba !important; /* Apply your custom border color */
        }
        

        .select2-selection:focus {
            border-color: #4f52ba !important; /* Maintain the same border color on focus */
            box-shadow: 0 0 5px rgba(79, 82, 186, 0.5) !important;
        }
        .select2-selection__placeholder {
            color: #4f52ba;
            font-size: 12px;
        }
        
        .select2-container--open .select2-selection {
            border-color: #4f52ba;
            box-shadow: 0 0 5px rgba(79, 82, 186, 0.5);
        }
        
        .select2-results__option {
            font-size: 12px;
            padding: 10px;
        }
        
        .select2-results__option--highlighted {
            background-color: #4f52ba !important;
            color: #fff;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tr:hover {
            background-color: rgba(79, 82, 186, 0.2);
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            width: 500px;
            position: relative;
        }

        .modal-content h2 {
            color: #595959;
            font-size: 20px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 25px;
        }

        .form-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            color: #595959;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-weight: normal;
            background-color: #fff;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #4f52ba;
            box-shadow: 0 0 5px rgba(79, 82, 186, 0.3);
            outline: none;
        }

        .add-button {
            background-color: #4f52ba;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 18.75%;
            margin-top: 10px;
        }

        .modal-close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 20px;
            color: #666;
            transition: color 0.3s ease;
        }

        .modal-close-btn:hover {
            color: #333;
        }

        .button-container {
            display: flex;
            justify-content: right;
            gap: 10px;
            margin-top: 20px;
        }
    </style>

<div class="main">
    <div class="container">
        <div class="text-right">
            <button class="add-button" onclick="openAddPerangkatModal()">Tambah Perangkat</button>
        </div>
        
        <div class="dropdown-container">
            <!-- Dropdown Region -->
            <select id="region" name="region[]" multiple>
                <option value="">Pilih Region</option>
                @foreach ($regions as $region)
                    <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                @endforeach
            </select>

            <!-- Dropdown Site -->
            <select id="site" name="site[]" multiple disabled>
                <option value="">Pilih Site</option>
            </select>

            <!-- Dropdown Perangkat -->
            <select id="perangkat" name="perangkat[]" multiple>
                <option value="">Pilih Perangkat</option>
                @foreach ($perangkatList as $perangkat)
                    <option value="{{ $perangkat->kode_pkt }}">{{ $perangkat->nama_pkt }}</option>
                @endforeach
            </select>

            <!-- Dropdown Brand -->
            <select id="brand" name="brand[]" multiple>
                <option value="">Pilih Brand</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->kode_brand }}">{{ $brand->nama_brand }}</option>
                @endforeach
            </select>

            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" id="searchInput" class="custom-select" placeholder="Cari" onkeyup="searchTable()" />
            </div>
        </div>

        <!-- Table Data -->
        <div class="table-container">
            <table id="tablePerangkat">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Region</th>
                        <th>POP</th>
                        <th>No Rack</th>
                        <th>Perangkat</th>
                        <th>Perangkat ke</th>
                        <th>Brand</th>
                        <th>Type</th>
                        <th>Uawal</th>
                        <th>Uakhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

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
                        <select id="siteAdd" name="kode_site" required>
                            <option value="">Pilih Site</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="perangkatAdd">Perangkat</label>
                        <select id="perangkatAdd" name="kode_pkt" required>
                            <option value="">Pilih Perangkat</option>
                            @foreach($perangkatList as $p)
                                <option value="{{ $p->kode_pkt }}">{{ $p->nama_pkt }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="brandAdd">Brand</label>
                        <select id="brandAdd" name="kode_brand" required>
                            <option value="">Pilih Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->kode_brand }}">{{ $brand->nama_brand }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="type">Type</label>
                        <input type="text" id="type" name="type" required>
                    </div>
                
                </div>

                <div class="right-column">
                    <div class="form-group">
                        <label for="no_rack">No Rack</label>
                        <input type="text" id="no_rack" name="no_rack" required>
                    </div>
                    <div class="form-group">
                        <label for="pkt_ke">Perangkat Ke</label>
                        <input type="text" id="pkt_ke" name="pkt_ke" required>
                    </div>

                    <div class="form-group">
                        <label for="uawal">U Awal</label>
                        <input type="text" id="uawal" name="uawal" required>
                    </div>

                    <div class="form-group">
                        <label for="uakhir">U Akhir</label>
                        <input type="text" id="uakhir" name="uakhir" required>
                    </div>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="add-button">Simpan</button>
            </div>
        </form>
    </div>
</div>



    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Initialize Select2 for dropdowns
            $('#region').select2({
                placeholder: "Pilih Region",
                allowClear: true
            });

            $('#site').select2({
                placeholder: "Pilih Site",
                allowClear: true
            });

            $('#perangkat').select2({
                placeholder: "Pilih Perangkat",
                allowClear: true
            });

            $('#brand').select2({
                placeholder: "Pilih Brand",
                allowClear: true
            });

            // Load perangkat data on page load
            loadPerangkatData();

            // Region change event
            $('#region').change(function() {
                const selectedRegions = $(this).val();
                $('#site').prop('disabled', true).empty().append('<option value="">Pilih Site</option>');

                if (selectedRegions.length > 0) {
                    $.get('/get-sites', { regions: selectedRegions }, function(data) {
                        $('#site').prop('disabled', false);
                        $.each(data, function(key, value) {
                            $('#site').append(new Option(value, key));
                        });
                    });
                }

                loadPerangkatData(selectedRegions);
            });

            // Site change event
            $('#site').change(function() {
                const selectedRegions = $('#region').val();
                const selectedSites = $(this).val();
                loadPerangkatData(selectedRegions, selectedSites);
            });

            // Perangkat change event
            $('#perangkat').change(function() {
                const selectedRegions = $('#region').val();
                const selectedSites = $('#site').val();
                const selectedPerangkat = $(this).val();
                const selectedBrands = $('#brand').val();
                loadPerangkatData(selectedRegions, selectedSites, selectedPerangkat, selectedBrands);
            });

            // Brand change event
            $('#brand').change(function() {
                const selectedRegions = $('#region').val();
                const selectedSites = $('#site').val();
                const selectedPerangkat = $('#perangkat').val();
                const selectedBrands = $(this).val();
                loadPerangkatData(selectedRegions, selectedSites, selectedPerangkat, selectedBrands);
            });

            // Handle form submission
            $('#addPerangkatForm').submit(function(e) {
                e.preventDefault();
                
                const wdm = $('#wdm-input').val();
                const url = wdm ? `/update-perangkat/${wdm}` : '/store-perangkat';
                const method = wdm ? 'PUT' : 'POST';
                
                $.ajax({
                    url: url,
                    type: method,
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            closeAddPerangkatModal();
                            showSwal('success', wdm ? 'Perangkat berhasil diupdate!' : 'Perangkat berhasil ditambahkan!');
                            loadPerangkatData();
                            $('#addPerangkatForm')[0].reset();
                            $('#wdm-input').remove();
                            $('h2').text('Tambah Perangkat Baru');
                            $('.add-button[type="submit"]').text('Simpan');
                        } else {
                            showSwal('error', response.message || 'Terjadi kesalahan');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        showSwal('error', 'Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });

            // Handle region change in modal
            $('#regionAdd').change(function() {
                const regionId = $(this).val();
                $('#siteAdd').empty().append('<option value="">Pilih Site</option>');
                
                if (regionId) {
                    $.get(`/get-sites`, { regions: [regionId] }, function(data) {
                        $.each(data, function(key, value) {
                            $('#siteAdd').append(new Option(value, key));
                        });
                    });
                }
            });

            // Tambahkan setelah jQuery loaded
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        // Function to load perangkat data
        function loadPerangkatData(regions = [], sites = [], perangkat = [], brands = []) {
            $.get('/get-perangkat', { region: regions, site: sites, perangkat: perangkat, brand: brands }, function(response) {
                const tbody = $('#tablePerangkat tbody');
                tbody.empty();

                if (response.perangkat.length === 0) {
                    tbody.append('<tr><td colspan="11" style="text-align: center;">Tidak ada data perangkat</td></tr>');
                    return;
                }

                $.each(response.perangkat, function(index, perangkat) {
                    tbody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${perangkat.nama_region}</td>
                            <td>${perangkat.nama_site || '-'}</td>
                            <td>${perangkat.no_rack || '-'}</td>
                            <td>${perangkat.nama_pkt || '-'}</td>
                            <td>${perangkat.pkt_ke || '-'}</td>
                            <td>${perangkat.nama_brand || '-'}</td>
                            <td>${perangkat.type || '-'}</td>
                            <td>${perangkat.uawal || '-'}</td>
                            <td>${perangkat.uakhir || '-'}</td>
                            <td>
                                <button onclick="editPerangkat(${perangkat.WDM})" 
                                    style="background-color: #4CAF50; color: white; border: none; padding: 5px 10px; border-radius: 3px; margin-right: 5px; cursor: pointer;">
                                    Edit
                                </button>
                                <button onclick="deletePerangkat(${perangkat.WDM})"
                                    style="background-color: #f44336; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `);
                });
            }).fail(function() {
                const tbody = $('#tablePerangkat tbody');
                tbody.empty().append('<tr><td colspan="11" style="text-align: center;">Terjadi kesalahan dalam memuat data</td></tr>');
            });
        }

        // Search table function
        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.querySelector('#tablePerangkat');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j] && cells[j].innerText.toLowerCase().includes(filter)) {
                        found = true;
                    }
                }

                rows[i].style.display = found ? '' : 'none';
            }
        }

        function openAddPerangkatModal() {
    document.getElementById("addPerangkatModal").style.display = "flex";
}

function closeAddPerangkatModal() {
    document.getElementById("addPerangkatModal").style.display = "none";
}

// Optional: close modal when clicking outside of the modal content
window.onclick = function(event) {
    const modal = document.getElementById("addPerangkatModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
};

    // Load sites based on selected region in the modal
    $('#regionAdd').change(function() {
        const selectedRegion = $(this).val();
        $('#siteAdd').prop('disabled', true).empty().append('<option value="">Pilih Site</option>');

        if (selectedRegion) {
            $.get('/get-sites', { regions: [selectedRegion] }, function(data) {
                $('#siteAdd').prop('disabled', false);
                $.each(data, function(key, value) {
                    $('#siteAdd').append(new Option(value, key));
                });
            });
        }
    });

    function addPerangkatSuccess() {
        showTemporaryMessage();
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
                confirmButtonColor: "#DD6B55",
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

    // Fungsi untuk edit perangkat
    function editPerangkat(wdm) {
        $.get(`/get-perangkat/${wdm}`, function(response) {
            if (response.success) {
                const perangkat = response.perangkat;
                
                // Reset form terlebih dahulu
                $('#addPerangkatForm')[0].reset();
                
                // Isi form dengan data yang ada
                $('#regionAdd').val(perangkat.kode_region).trigger('change');
                
                // Tunggu sebentar untuk memastikan site sudah ter-load
                setTimeout(() => {
                    $('#siteAdd').val(perangkat.kode_site);
                    $('#perangkatAdd').val(perangkat.kode_pkt);
                    $('#brandAdd').val(perangkat.kode_brand);
                    $('#no_rack').val(perangkat.no_rack);
                    $('#pkt_ke').val(perangkat.pkt_ke);
                    $('#type').val(perangkat.type);
                    $('#uawal').val(perangkat.uawal);
                    $('#uakhir').val(perangkat.uakhir);
                }, 1000);
                
                // Hapus input hidden WDM yang mungkin ada sebelumnya
                $('#wdm-input').remove();
                
                // Tambahkan WDM ke form untuk keperluan update
                $('#addPerangkatForm').append(`<input type="hidden" id="wdm-input" name="wdm" value="${perangkat.WDM}">`);
                
                // Ubah judul modal dan text tombol
                $('h2').text('Edit Perangkat');
                $('.add-button[type="submit"]').text('Update');
                
                // Tampilkan modal
                openAddPerangkatModal();
            }
        });
    }

    // Modifikasi fungsi deletePerangkat
    function deletePerangkat(wdm) {
        swal({
            title: "Apakah Anda yakin?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal",
            closeOnConfirm: false
        }, function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: `/delete-perangkat/${wdm}`,
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            swal({
                                title: "Terhapus!",
                                text: "Perangkat berhasil dihapus.",
                                type: "success",
                                button: {
                                    text: "OK",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-primary"
                                }
                            });
                            loadPerangkatData();
                        } else {
                            swal({
                                title: "Error!",
                                text: response.message || "Gagal menghapus perangkat",
                                type: "error",
                                button: {
                                    text: "OK",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-danger"
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Delete error details:', {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });
                        swal({
                            title: "Error!",
                            text: "Terjadi kesalahan saat menghapus perangkat",
                            type: "error",
                            button: {
                                text: "OK",
                                value: true,
                                visible: true,
                                className: "btn btn-danger"
                            }
                        });
                    }
                });
            }
        });
    }
    </script>
@endsection
