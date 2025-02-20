@extends('layouts.sidebar')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

    <head>
        <link rel="stylesheet" href="{{ asset('css/general.css') }}">        
        <link rel="stylesheet" href="{{ asset(path: 'css/tabel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
        <link rel="stylesheet" href="{{ asset('css/filter.css') }}">
        <script src="https://kit.fontawesome.com/bdb0f9e3e2.js" crossorigin="anonymous"></script>
    </head>

    <div class="main">
        <div class="container">
            <div class="header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Data Alatukur</h3>
                    @if(auth()->user()->role == '1')
                        <button class="add-button" onclick="openAddAlatukurModal()">Tambah Alatukur</button>
                    @endif  
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
            tbody.empty().append('<tr><td colspan="8" class="text-center">Terjadi kesalahan dalam memuat data</td></tr>');
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
    </script>
@endsection