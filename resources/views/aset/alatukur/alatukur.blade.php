@extends('layouts.sidebar')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

    <head>
        <link rel="stylesheet" href="{{ asset('css/general.css') }}">        
        <link rel="stylesheet" href="{{ asset('css/tabelaset.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
        <link rel="stylesheet" href="{{ asset('css/filter.css') }}">
        <script src="https://kit.fontawesome.com/bdb0f9e3e2.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>

    <div class="main">
        <div class="container">
        <div class="header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Data Alat ukur</h3>
                    <div style="display: flex; gap: 10px;">
                        @if(auth()->user()->role == '1')
                            <button class="add-button" style="width: 150px;" onclick="openAddAlatukurModal()">Tambah Alatukur</button>
                            <button class="add-button" style="width: 150px;" onclick="importData()">Import Alatukur</button>
                            <button class="add-button" style="width: 150px; margin-left: 10px;" onclick="showExportModal()">Export Alatukur</button>
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
                    <select id="jenisalatukur" name="jenisalatukur[]" multiple data-placeholder="Pilih Jenis Alatukur">
                        <option value="" disabled>Pilih Jenis Alatukur</option>
                        @foreach ($listpkt as $alatukur)
                            <option value="{{ $alatukur->kode_alatukur }}">{{ $alatukur->nama_alatukur }}</option>
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
                <table id="tableAlatukur">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No</th>
                            <th>Hostname</th>
                            <th>Region</th>
                            <th>Alat ukur</th>
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

    @include('aset.alatukur.add-alatukur')
    @include('aset.alatukur.edit-alatukur')
    @include('aset.alatukur.lihat-alatukur')
    @include('aset.alatukur.import-alatukur')

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
                    <label for="regionSelect">Pilih Region:</label>
                    <select id="regionSelect" name="region" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
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
            <img src="{{ asset('images/loading.gif') }}" alt="Loading" />
        </div>
    </div>

    <style>
        
    </style>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#region, #jenisalatukur, #brand').select2({
            placeholder: function() {
                return $(this).data('placeholder');
            },
            allowClear: true
        });

        ['#region', '#jenisalatukur', '#brand'].forEach(selector => {
            $(selector).change(function() {
                LoadData(
                    $('#region').val(),
                    $('#jenisalatukur').val(),
                    $('#brand').val()
                );
            });
        });
        LoadData();
    });

    function LoadData(regions = [], jenisalatukur = [], brands = []) {
        $.get('/get-alatukur', { region: regions, jenisalatukur: jenisalatukur, brand: brands }, function(response) {
            const tbody = $('#tableAlatukur tbody');
            tbody.empty();

            if (response.alatukur.length === 0) {
                tbody.append('<tr><td colspan="8" class="text-center">Tidak ada data alatukur</td></tr>');
                return;
            }

            $.each(response.alatukur, function(index, alatukur) {
                const kodeAlatukur = [
                    alatukur.kode_region, 
                    alatukur.kode_alatukur, 
                    alatukur.alatukur_ke, 
                    alatukur.kode_brand, 
                    alatukur.type
                ].filter(val => val !== null && val !== undefined && val !== '').join('-');

                const actionButtons = `
                    <button onclick="lihatAlatukur(${alatukur.id_alatukur})"
                        style="background-color: #9697D6; color: white; border: none; padding: 5px 10px; border-radius: 3px; margin-right: 5px; cursor: pointer;">
                        <i class="fa-solid fa-eye"></i>
                    </button>

                    @if(auth()->user()->role == '1')
                    <button onclick="editAlatukur(${alatukur.id_alatukur})" 
                        style="background-color: #4f52ba; color: white; border: none; padding: 5px 10px; border-radius: 3px; margin-right: 5px; cursor: pointer;">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                        <button onclick="deleteAlatukur(${alatukur.id_alatukur})"
                            style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    @endif
                `;

                tbody.append(`
                    <tr>
                        <td></td>
                        <td>${index + 1}</td>
                        <td>${kodeAlatukur || '-'}</td>
                        <td>${alatukur.nama_region}</td>
                        <td>${alatukur.nama_alatukur || '-'}</td>
                        <td>${alatukur.nama_brand || '-'}</td>
                        <td>${alatukur.type || '-'}</td>
                        <td>${actionButtons}</td>
                    </tr>
                `);
            });
        }).fail(function() {
            const tbody = $('#tableAlatukur tbody');
            tbody.empty().append('<tr><td colspan="8" class="text-align: center">Terjadi kesalahan dalam memuat data</td></tr>');
        });
    }

    function deleteAlatukur(id_alatukur) {
        console.log("Delete function called with id_alatukur:", id_alatukur); // Debugging line
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
                    url: `/delete-alatukur/${id_alatukur}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Terhapus!', 'Alatukur berhasil dihapus.', 'success');
                            LoadData();
                        } else {
                            Swal.fire('Error!', response.message || 'Terjadi kesalahan saat menghapus', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        Swal.fire('Error!', 'Terjadi kesalahan saat menghapus alatukur', 'error');
                    }
                });
            }
        });
    }

    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const table = document.querySelector('#tableAlatukur');
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
        document.getElementById('importModal').style.display = 'flex';
    }

    function closeImportModal() {
    document.getElementById('importModal').style.display = 'none';
    
    // Reset input file agar pengguna bisa upload file baru tanpa refresh
    document.getElementById('importFile').value = '';
}


    function submitImport() {
        const fileInput = document.getElementById('importFile');
        const file = fileInput.files[0];
        
        if (!file) {
            Swal.fire({
                title: "Error!",
                text: "Silakan pilih file terlebih dahulu.",
                icon: "error",
                confirmButtonText: "OK"
            });
            return;
        }

        const formData = new FormData();
        formData.append('file', file);

        document.getElementById('importLoadingIndicator').style.display = 'block';

        $.ajax({
            url: '{{ route("alatukur.import") }}',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                closeImportModal();
                if (response.success) {
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Data alatukur berhasil diimpor.",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(() => {
                        LoadData();
                        closeImportModal();
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
                console.error(xhr.responseText);
                Swal.fire({
                    title: "Error!",
                    text: "Terjadi kesalahan saat mengunggah file.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            },
            complete: function() {
                document.getElementById('importLoadingIndicator').style.display = 'none';
            }
        });
    }

    function showExportModal() {
        document.getElementById('exportModal').style.display = 'flex'; // Menampilkan modal
    }

    function closeExportModal() {
        document.getElementById('exportModal').style.display = 'none'; // Menyembunyikan modal
    }

    function exportData() {
        const isAllDataChecked = document.getElementById('exportAll').checked; // Cek apakah checkbox dicentang
        const selectedRegion = document.getElementById('regionSelect').value; // Ambil nilai dari dropdown region
        const exportOption = isAllDataChecked ? 'all' : 'unique'; // Tentukan opsi ekspor berdasarkan checkbox

        // Sembunyikan modal ekspor
        closeExportModal();

        // Tampilkan indikator loading
        document.getElementById('loadingIndicator').style.display = 'block';

        // Mengirim permintaan ke server untuk mengekspor data
        $.ajax({
            url: '{{ route("alatukur.export") }}', // Pastikan rute ini sesuai
            type: 'POST', // Pastikan ini adalah POST
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { option: exportOption, region: selectedRegion }, // Kirim region yang dipilih
            success: function(response) {
                if (response.success) {
                    // Jika berhasil, arahkan ke URL file yang dihasilkan
                    window.location.href = response.file_url; // Mengunduh file
                } else {
                    Swal.fire('Gagal!', response.message, 'error'); // Tampilkan pesan kesalahan yang lebih spesifik
                }
            },
            error: function(xhr) {
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
@endsection