@extends('layouts.sidebar')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">


    <head>
        <link rel="stylesheet" href="{{ asset('css/general.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tabelaset.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
        <link rel="stylesheet" href="{{ asset('css/filter.css') }}">
        <script src="https://kit.fontawesome.com/bdb0f9e3e2.js" crossorigin="anonymous"></script>
        <style>
            .select2-container {
                width: 100% !important; /* Pastikan select2 menggunakan lebar penuh */
            }

            .select2-selection {
                height: 40px; /* Ubah tinggi select2 sesuai kebutuhan */
            }
        </style>
    </head>

    <div class="main">
        <div class="container">
            <div class="header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Data Fasilitas</h3>
                    <div class="button-container">
                        @if(auth()->user()->role == '1')
                        <button class="add-button" style="width: 150px;" onclick="importData()">Import</button>
                            <button class="add-button" style="width: 150px;" onclick="openAddFasilitasModal()">Tambah Fasilitas</button>
                        <button class="add-button" style="width: 150px;" onclick="openExportModal()">Export</button>
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
                    <select id="jenisfasilitas" name="jenisfasilitas[]" multiple data-placeholder="Pilih Jenis Fasilitas">
                        <option value="" disabled>Pilih Jenis Fasilitas</option>
                        @foreach ($listpkt as $fasilitas)
                            <option value="{{ $fasilitas->kode_fasilitas }}">{{ $fasilitas->nama_fasilitas }}</option>
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
                <table id="tableFasilitas">
                    <thead>
                        <tr>
                            <th onclick="sortTableByStatus()" style="cursor: pointer;">
                                <i id="statusSortIcon" class="fa-solid fa-sort"></i>
                            </th>
                            <th>No</th>
                            <th>Hostname</th>
                            <th>Region</th>
                            <th>POP</th>
                            <th>Fasilitas</th>
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
    
    @include('aset.fasilitas.add-fasilitas')
    @include('aset.fasilitas.edit-fasilitas')
    @include('aset.fasilitas.lihat-fasilitas')


    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
    $(document).ready(function() {
        // Update the Select2 initialization
        $('#region, #site, #jenisfasilitas, #brand').select2({
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

            ['#site', '#jenisfasilitas', '#brand'].forEach(selector => {
            $(selector).change(function() {
                LoadData(
                    $('#region').val(),
                    $('#site').val(),
                    $('#jenisfasilitas').val(),
                    $('#brand').val()
                );
            });
        });
            LoadData();
        });

        function LoadData(regions = [], sites = [], jenisfasilitas = [], brands = []) {
        $.get('/get-fasilitas', { 
            region: regions, 
            site: sites, 
            jenisfasilitas: jenisfasilitas,
            brand: brands 
        }, function(response) {
            const tbody = $('#tableFasilitas tbody');
            tbody.empty();

            if (response.fasilitas.length === 0) {
                tbody.append('<tr><td colspan="8" class="text-center">Tidak ada data fasilitas</td></tr>');
                return;
            }

            $.each(response.fasilitas, function(index, fasilitas) {
                const kodeFasilitas = [
                    fasilitas.kode_region, 
                    fasilitas.kode_site, 
                    fasilitas.no_rack, 
                    fasilitas.kode_fasilitas, 
                    fasilitas.fasilitas_ke, 
                    fasilitas.kode_brand, 
                    fasilitas.type
                ].filter(val => val !== null && val !== undefined && val !== '').join('-');

                const statusColor = fasilitas.no_rack ? "green" : "red";
                const statusTd = `<td style="text-align: center;">
                        <div style="background-color: ${statusColor}; width: 15px; height: 15px; border-radius: 3px; display: inline-block;"></div>
                </td>`;

                const actionButtons = `
                <button onclick="lihatFasilitas(${fasilitas.id_fasilitas})"
                    style="background-color: #9697D6; color: white; border: none; padding: 5px 10px; border-radius: 3px; margin-right: 5px; cursor: pointer;">
                    <i class="fa-solid fa-eye"></i>
                </button>
                @if(auth()->user()->role == '1')
                    <button onclick="editFasilitas(${fasilitas.id_fasilitas})" 
                        style="background-color: #4f52ba; color: white; border: none; padding: 5px 10px; border-radius: 3px; margin-right: 5px; cursor: pointer;">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button onclick="deleteFasilitas(${fasilitas.id_fasilitas})"
                        style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                @endif
            `;

                tbody.append(`
                    <tr>
                        ${statusTd}
                        <td>${index + 1}</td>
                        <td>${kodeFasilitas || '-'}</td>
                        <td>${fasilitas.nama_region}</td>
                        <td>${fasilitas.nama_site || '-'}</td>
                        <td>${fasilitas.nama_fasilitas || '-'}</td>
                        <td>${fasilitas.nama_brand || '-'}</td>
                        <td>${fasilitas.type || '-'}</td>
                        <td>${actionButtons}</td>
                    </tr>
                `);
            });
        }).fail(function() {
            const tbody = $('#tableFasilitas tbody');
            tbody.empty().append('<tr><td colspan="8" class="text-center">Terjadi kesalahan dalam memuat data</td></tr>');
        });
    }


let originalRows = [];
let sortState = 0; 

function sortTableByStatus() {
    const table = document.getElementById("tableFasilitas");
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


    function deleteFasilitas(id_fasilitas) {
        console.log("Delete function called with id_fasilitas:", id_fasilitas); // Debugging line
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
                    url: `/delete-fasilitas/${id_fasilitas}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Terhapus!', 'Fasilitas berhasil dihapus.', 'success');
                            LoadData();
                        } else {
                            Swal.fire('Error!', response.message || 'Terjadi kesalahan saat menghapus', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        Swal.fire('Error!', 'Terjadi kesalahan saat menghapus fasilitas', 'error');
                    }
                });
            }
        });
    }

    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const table = document.querySelector('#tableFasilitas');
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

    function importData() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.csv, .xlsx, .xls'; // Format yang diterima
        input.onchange = (event) => {
            const file = event.target.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('file', file);

                $.ajax({
                    url: '{{ route("fasilitas.import") }}', // Pastikan ini sesuai dengan route yang benar
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Data berhasil diimpor.",
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(() => {
                                location.reload(); // Muat ulang halaman untuk melihat perubahan
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal!",
                                text: response.message,
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: "Error!",
                            text: "Terjadi kesalahan saat mengunggah file. Lihat konsol untuk detail.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            }
        };
        input.click(); // Membuka dialog file
    }

    function openExportModal() {
        document.getElementById('exportModal').style.display = 'flex'; // Menggunakan flex untuk memusatkan
    }

    function closeExportModal() {
        document.getElementById('exportModal').style.display = 'none'; // Menyembunyikan modal
    }

    function exportData() {
        const isAllDataChecked = document.getElementById('exportAll').checked; // Cek apakah checkbox dicentang
        const selectedRegion = document.getElementById('roSelect').value; // Ambil nilai dari dropdown region
        const exportOption = isAllDataChecked ? 'all' : 'unique'; // Tentukan opsi ekspor berdasarkan checkbox

        console.log('Opsi ekspor yang dipilih:', exportOption); // Log opsi yang dipilih

        // Sembunyikan modal ekspor
        closeExportModal();

        // Tampilkan indikator loading
        document.getElementById('loadingIndicator').style.display = 'block';

        // Mengirim permintaan ke server untuk mengekspor data
        $.ajax({
            url: '{{ route("fasilitas.export") }}',
            type: 'POST', // Pastikan ini adalah POST
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { option: exportOption, region: selectedRegion }, // Kirim region yang dipilih
            success: function(response) {
                console.log('Response dari server:', response); // Log response dari server
                if (response.success) {
                    // Jika berhasil, arahkan ke URL file PDF yang dihasilkan
                    window.location.href = response.file_url; // Mengunduh PDF
                } else {
                    Swal.fire('Gagal!', response.message, 'error'); // Tampilkan pesan kesalahan yang lebih spesifik
                }
            },
            error: function(xhr) {
                console.error('Kesalahan saat mengirim permintaan:', xhr); // Log kesalahan saat permintaan gagal
                const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat mengekspor data.';
                Swal.fire('Error!', errorMessage, 'error'); // Tampilkan pesan kesalahan yang lebih spesifik
            },
            complete: function() {
                // Sembunyikan indikator loading setelah proses selesai
                document.getElementById('loadingIndicator').style.display = 'none';
            }
        });
    }
    </script>

    <!-- Modal Export -->
    <div id="exportModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <span class="modal-close-btn" onclick="closeExportModal()">&times;</span>
            <h2>Pilih Opsi Ekspor</h2>
            <form id="exportForm">
                <div>
                    <label>
                        <input type="checkbox" name="export_option" value="all" id="exportAll"> 
                        <span style="margin-left: 10px;">Semua Data</span>
                    </label>
                </div>
                <div style="margin-top: 15px;">
                    <label for="roSelect">Pilih Region:</label>
                    <select id="roSelect" name="ro_option" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                        <option value="">Pilih Region</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="exportData()" class="export-button">Ekspor</button>
                </div>
            </form>
        </div>
        <!-- Indikator Loading -->
        <div id="loadingIndicator" style="display: none; text-align: center; margin-top: 20px;">
            <p>Loading... Mohon tunggu.</p>
            <img src="path/to/loading.gif" alt="Loading" /> <!-- Ganti dengan path ke gambar loading Anda -->
        </div>
    </div>

    <style>
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