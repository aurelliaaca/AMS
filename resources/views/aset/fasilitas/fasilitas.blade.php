@extends('layouts.sidebar')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

    <head>
        <link rel="stylesheet" href="{{ asset('css/general.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
        <link rel="stylesheet" href="{{ asset('css/filter.css') }}">
        <script src="https://kit.fontawesome.com/bdb0f9e3e2.js" crossorigin="anonymous"></script>
    </head>

    <div class="main">
        <div class="container">
            <div class="header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Data Fasilitas</h3>
                    @if(auth()->user()->role == '1')
                        <button class="add-button" onclick="openAddFasilitasModal()">Tambah Fasilitas</button>
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
                            <th></th>
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
    </script>
@endsection