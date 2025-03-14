@extends('layouts.sidebar')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

    <head>
        <link rel="stylesheet" href="{{ asset('css/general.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tabelaset.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
        <link rel="stylesheet" href="{{ asset('css/filter.css') }}">
    </head>
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/bdb0f9e3e2.js" crossorigin="anonymous"></script>

    <div class="main">
        <div class="container">
            <div class="header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Data Perangkat</h3>
                    <div style="display: flex; gap: 10px;">
                        @if(auth()->user()->role == '1')
                            <button class="add-button" onclick="openAddPerangkatModal()">Tambah Perangkat</button>
                            <button class="add-button" onclick="openImportPerangkatModal()">Import Perangkat</button>
                            <button class="add-button" onclick="openExportPerangkatModal()">Export Perangkat</button>
                        @endif             
                    </div>
                </div>
            </div>
            
            <div class="filter-container">
                <div>
                    <select id="region" name="region[]" multiple data-placeholder="Pilih Region">
                        <option value="" disabled>Pilih Region</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select id="site" name="site[]" multiple data-placeholder="Pilih Site" disabled>
                        <option value="" disabled>Pilih Site</option>
                    </select>
                </div>

                <div>
                    <select id="jenisperangkat" name="jenisperangkat[]" multiple data-placeholder="Pilih Jenis Perangkat">
                        <option value="" disabled>Pilih Jenis Perangkat</option>
                        @foreach ($listpkt as $perangkat)
                            <option value="{{ $perangkat->kode_perangkat }}">{{ $perangkat->nama_perangkat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select id="brand" name="brand[]" multiple data-placeholder="Pilih Brand">
                        <option value="" disabled>Pilih Brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->kode_brand }}">{{ $brand->nama_brand }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="search-bar">
                    <input type="text" id="searchInput" class="custom-select" placeholder="Cari" onkeyup="searchTable()" />
                </div>
            </div>

            <div class="table-container">
                <table id="tablePerangkat">
                    <thead>
                        <tr>
                            <th onclick="sortTableByStatus()" style="cursor: pointer;">
                                <i id="statusSortIcon" class="fa-solid fa-sort"></i>
                            </th>
                            <th>No</th>
                            <th>Hostname</th>
                            <th>Region</th>
                            <th>POP</th>
                            <th>No Rack</th>
                            <th>Perangkat</th>
                            <th>Brand</th>
                            <th>Type</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    @include('aset.perangkat.add-perangkat')
    @include('aset.perangkat.edit-perangkat')
    @include('aset.perangkat.lihat-perangkat')
    @include('aset.perangkat.import-perangkat')

    <!-- Modal Export Perangkat -->
    <div id="exportPerangkatModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <span class="modal-close-btn" onclick="closeExportPerangkatModal()">&times;</span>
            <h2>Pilih Opsi Ekspor</h2>
            <form id="exportPerangkatForm">
                <div>
                    <label>
                        <input type="checkbox" name="export_option" value="all" id="exportAllPerangkat"> 
                        <span style="margin-left: 10px;">Semua Data</span>
                    </label>
                </div>
                <div style="margin-top: 15px;">
                    <label for="roSelectPerangkat">Pilih Region:</label>
                    <select id="roSelectPerangkat" name="ro_option" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                        <option value="">Pilih Region</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="exportPerangkatData()" class="export-button">Ekspor</button>
                </div>
            </form>
        </div>
        <div id="loadingIndicatorPerangkat" style="display: none; text-align: center; margin-top: 20px;">
            <p>Loading... Mohon tunggu.</p>
            <img src="path/to/loading.gif" alt="Loading" />
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function() {
        // Update the Select2 initialization
        $('#region, #site, #jenisperangkat, #brand').select2({
            placeholder: function() {
                return $(this).data('placeholder');
            },
            allowClear: true
        });

            // Event handlers untuk filter
            $('#region').change(function() {
                const selectedRegions = $(this).val();
                $('#site').prop('disabled', true).empty().append('<option value="">Pilih Site</option>');

                if (selectedRegions && selectedRegions.length > 0) {
                    $.get('/get-sites', { regions: selectedRegions }, function(data) {
                        $('#site').prop('disabled', false);
                        $.each(data, function(key, value) {
                            $('#site').append(new Option(value, key));
                        });
                    });
                }

                LoadData(selectedRegions);
            });

            ['#site', '#jenisperangkat', '#brand'].forEach(selector => {
            $(selector).change(function() {
                LoadData(
                    $('#region').val(),
                    $('#site').val(),
                    $('#jenisperangkat').val(),
                    $('#brand').val()
                );
            });
        });

            // Inisialisasi awal
            LoadData();
        });

        function LoadData(regions = [], sites = [], jenisperangkat = [], brands = []) {
    $.get('/get-perangkat', { 
        region: regions, 
        site: sites, 
        jenisperangkat: jenisperangkat,
        brand: brands 
    }, function(response) {
        const tbody = $('#tablePerangkat tbody');
        tbody.empty();

        if (response.perangkat.length === 0) {
            tbody.append('<tr><td colspan="9" class="text-center">Tidak ada data perangkat</td></tr>');
            return;
        }

        $.each(response.perangkat, function(index, perangkat) {
            const kodePerangkat = [
                perangkat.kode_region, 
                perangkat.kode_site, 
                perangkat.no_rack, 
                perangkat.kode_perangkat, 
                perangkat.perangkat_ke, 
                perangkat.kode_brand, 
                perangkat.type
            ].filter(val => val !== null && val !== undefined && val !== '').join('-');

            const statusColor = perangkat.no_rack ? "green" : "red";
            const statusTd = `<td style="text-align: center;">
                    <div style="background-color: ${statusColor}; width: 15px; height: 15px; border-radius: 3px; display: inline-block;"></div>
                  </td>`;

            const actionButtons = `
                <button onclick="lihatPerangkat(${perangkat.id_perangkat})" 
                    style="background-color: #9697D6; color: white; border: none; padding: 5px 10px; border-radius: 3px; margin-right: 5px; cursor: pointer;">
                    <i class="fa-solid fa-eye"></i>
                </button>
                @if(auth()->user()->role == '1')
                    <button onclick="editPerangkat(${perangkat.id_perangkat})" 
                        style="background-color: #4f52ba; color: white; border: none; padding: 5px 10px; border-radius: 3px; margin-right: 5px; cursor: pointer;">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button onclick="deletePerangkat(${perangkat.id_perangkat})"
                        style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                @endif
            `;

            tbody.append(`
                <tr>
                    ${statusTd}
                    <td>${index + 1}</td>
                    <td>${kodePerangkat || '-'}</td>
                    <td>${perangkat.nama_region}</td>
                    <td>${perangkat.nama_site || '-'}</td>
                    <td>${perangkat.no_rack || '-'}</td>
                    <td>${perangkat.nama_perangkat || '-'}</td>
                    <td>${perangkat.nama_brand || '-'}</td>
                    <td>${perangkat.type || '-'}</td>
                    <td>${actionButtons}</td>
                </tr>
            `);
        });
    }).fail(function() {
        const tbody = $('#tablePerangkat tbody');
        tbody.empty().append('<tr><td colspan="9" class="text-center">Terjadi kesalahan dalam memuat data</td></tr>');
    });
}

let originalRows = [];
let sortState = 0; 

function sortTableByStatus() {
    const table = document.getElementById("tablePerangkat");
    const tbody = table.querySelector("tbody");

    // Initialize originalRows only once
    if (originalRows.length === 0) {
        const rows = Array.from(tbody.querySelectorAll("tr"));
        originalRows = rows.map(row => row.cloneNode(true)); // Store a clone of the original rows
    }

    const rows = Array.from(tbody.querySelectorAll("tr"));

    if (sortState === 0) {
        // If currently unsorted, set to ascending
        sortState = 1;
        document.getElementById("statusSortIcon").className = "fa-solid fa-sort-up";
    } else if (sortState === 1) {
        // If currently ascending, set to descending
        sortState = 2;
        document.getElementById("statusSortIcon").className = "fa-solid fa-sort-down";
    } else {
        // If currently descending, reset to unsorted
        sortState = 0;
        document.getElementById("statusSortIcon").className = "fa-solid fa-sort";
        tbody.innerHTML = ""; // Clear current rows
        originalRows.forEach(row => tbody.appendChild(row.cloneNode(true))); // Restore original rows
        return; // Exit the function
    }

    // Sort rows based on the current state
    rows.sort((a, b) => {
        const statusA = a.cells[0].querySelector("div").style.backgroundColor === "green" ? 1 : 0;
        const statusB = b.cells[0].querySelector("div").style.backgroundColor === "green" ? 1 : 0;

        return sortState === 1 ? statusA - statusB : statusB - statusA;
    });

    // Clear and append sorted rows
    tbody.innerHTML = "";
    rows.forEach(row => tbody.appendChild(row));
}


    function deletePerangkat(id_perangkat) {
        console.log("Delete function called with id_perangkat:", id_perangkat); // Debugging line
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f52ba',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/delete-perangkat/${id_perangkat}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Terhapus!', 'Perangkat berhasil dihapus.', 'success');
                            LoadData();
                        } else {
                            Swal.fire('Error!', response.message || 'Terjadi kesalahan saat menghapus', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        Swal.fire('Error!', 'Terjadi kesalahan saat menghapus perangkat', 'error');
                    }
                });
            }
        });
    }

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
    
    function openImportPerangkatModal() {
        document.getElementById('importPerangkatModal').style.display = 'block';
    }

    function closeImportPerangkatModal() {
        document.getElementById('importPerangkatModal').style.display = 'none';
        document.getElementById('importPerangkatForm').reset();
    }

    // Handle Import Form Submission
    $('#importPerangkatForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        $.ajax({
            url: '/import-perangkat',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                closeImportPerangkatModal();
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data perangkat berhasil diimport',
                        confirmButtonColor: '#4f52ba'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            LoadData(); // Refresh the table
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message || 'Terjadi kesalahan saat import data',
                        confirmButtonColor: '#4f52ba'
                    });
                }
            },
            error: function(xhr) {
                closeImportPerangkatModal();
                let errorMessage = 'Terjadi kesalahan saat import data';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMessage,
                    confirmButtonColor: '#4f52ba'
                });
            }
        });
    });

    function openExportPerangkatModal() {
        document.getElementById('exportPerangkatModal').style.display = 'flex';
    }

    function closeExportPerangkatModal() {
        document.getElementById('exportPerangkatModal').style.display = 'none';
    }

    function exportPerangkatData() {
        const isAllDataChecked = document.getElementById('exportAllPerangkat').checked;
        const selectedRegion = document.getElementById('roSelectPerangkat').value;
        const exportOption = isAllDataChecked ? 'all' : 'unique';

        closeExportPerangkatModal();
        document.getElementById('loadingIndicatorPerangkat').style.display = 'block';

        $.ajax({
            url: '{{ route("perangkat.export") }}',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { option: exportOption, region: selectedRegion },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.file_url;
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON && xhr.responseJSON.message 
                    ? xhr.responseJSON.message 
                    : 'Terjadi kesalahan saat mengekspor data.';
                Swal.fire('Error!', errorMessage, 'error');
            },
            complete: function() {
                document.getElementById('loadingIndicatorPerangkat').style.display = 'none';
            }
        });
    }
    </script>

    <style>
    /*EXPORT*/
    .modal-overlay {
        position: fixed; /* Mengatur posisi tetap */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7); /* Latar belakang semi-transparan */
        display: flex; /* Menggunakan flexbox untuk memusatkan konten */
        justify-content: center; /* Memusatkan secara horizontal */
        align-items: center; /* Memusatkan secara vertikal */
        z-index: 1000; /* Pastikan modal berada di atas elemen lain */
        }

    .modal-content {
        background-color: white; /* Latar belakang konten modal */
        padding: 20px; /* Padding di dalam konten modal */
        border-radius: 5px; /* Sudut melengkung */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5); /* Bayangan untuk efek kedalaman */
        width: 400px; /* Lebar konten modal */
        max-width: 90%; /* Maksimal lebar 90% dari viewport */
        position: relative; /* Pastikan konten modal memiliki posisi relatif */
        }

        .modal-close-btn {
        cursor: pointer; /* Pointer saat hover */
        font-size: 20px; /* Ukuran font untuk tombol tutup */
        float: right; /* Mengatur posisi tombol tutup di kanan */
        }

    .export-button {
        background-color: #4f52ba; /* Warna latar belakang tombol */
        color: white; /* Warna teks tombol */
        border: none; /* Menghilangkan border default */
        padding: 10px; /* Padding tombol */
        border-radius: 5px; /* Sudut melengkung tombol */
        cursor: pointer; /* Pointer saat hover */
        font-size: 16px; /* Ukuran font tombol */
        transition: background-color 0.3s; /* Transisi warna latar belakang */
        width: 100%; /* Tombol mengisi lebar penuh */
        }

    .export-button:hover {
        background-color: #6f86e0; /* Warna latar belakang saat hover */
        }
    
    .modal-footer {
        display: flex; /* Menggunakan flexbox untuk mengatur posisi */
        justify-content: flex-end; /* Mengatur tombol ke kanan */
        margin-top: 20px; /* Jarak atas untuk pemisahan */
        }
    </style>

@endsection