@extends('layouts.sidebar')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

    <head>
        <link rel="stylesheet" href="{{ asset('css/aset.css') }}">
    </head>

    <div class="main">
        <div class="container">
            <div class="header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 18px; font-weight: 600; color: #4f52ba; margin: 0;">Data Perangkat</h3>
                    <button class="add-button" onclick="openAddPerangkatModal()">Tambah Perangkat</button>
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
                    <select id="perangkat" name="perangkat[]" multiple data-placeholder="Pilih Perangkat">
                        <option value="" disabled>Pilih Perangkat</option>
                        @foreach ($listpkt as $perangkat)
                            <option value="{{ $perangkat->kode_pkt }}">{{ $perangkat->nama_pkt }}</option>
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
                            <th>No</th>
                            <th>Hostname</th>
                            <th>Region</th>
                            <th>POP</th>
                            <th>No Rack</th>
                            <th>Perangkat</th>
                            <th>Brand</th>
                            <th>Type</th>
                            <!-- <th>Uawal</th> -->
                            <!-- <th>Uakhir</th> -->
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


    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
    $(document).ready(function() {
        // Inisialisasi Select2 dengan placeholder
        $('#region, #site, #perangkat, #brand').select2({
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

        ['#site', '#perangkat', '#brand'].forEach(selector => {
            $(selector).change(function() {
                LoadData(
                    $('#region').val(),
                    $('#site').val(),
                    $('#perangkat').val(),
                    $('#brand').val()
                );
            });
        });

        // Inisialisasi awal
        LoadData();
    });

    function LoadData(regions = [], sites = [], perangkat = [], brands = []) {
        $.get('/get-perangkat', { 
            region: regions, 
            site: sites, 
            perangkat: perangkat, 
            brand: brands 
        }, function(response) {
            const tbody = $('#tablePerangkat tbody');
            const values = [
                perangkat.kode_region,
                perangkat.kode_site,
                perangkat.no_rack,
                perangkat.kode_pkt,
                perangkat.pkt_ke,
                perangkat.kode_brand,
                perangkat.type
            ];
            tbody.empty();

            if (response.perangkat.length === 0) {
                tbody.append('<tr><td colspan="8" class="text-center">Tidak ada data perangkat</td></tr>');
                return;
            }

            $.each(response.perangkat, function(index, perangkat) {
                const kodePerangkat = [
                    perangkat.kode_region, 
                    perangkat.kode_site, 
                    perangkat.no_rack, 
                    perangkat.kode_pkt, 
                    perangkat.pkt_ke, 
                    perangkat.kode_brand, 
                    perangkat.type
                ].filter(val => val !== null && val !== undefined && val !== '').join('-');

                tbody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${kodePerangkat || '-'}</td>
                        <td>${perangkat.nama_region}</td>
                        <td>${perangkat.nama_site || '-'}</td>
                        <td>${perangkat.no_rack || '-'}</td>
                        <td>${perangkat.nama_pkt || '-'}</td>
                        <td>${perangkat.nama_brand || '-'}</td>
                        <td>${perangkat.type || '-'}</td>
                        <td>
                            <button onclick="lihatPerangkat(${perangkat.id_perangkat})"
                                style="background-color: #9697D6; color: white; border: none; padding: 5px 10px; border-radius: 3px; margin-right: 5px; cursor: pointer;">
                                Lihat detail
                            </button>
                            <button onclick="editPerangkat(${perangkat.id_perangkat})" 
                                style="background-color: #4f52ba; color: white; border: none; padding: 5px 10px; border-radius: 3px; margin-right: 5px; cursor: pointer;">
                                Edit
                            </button>
                            <button onclick="deletePerangkat(${perangkat.id_perangkat})"
                                style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                                Delete
                            </button>
                        </td>
                    </tr>
                `);
            });
        }).fail(function() {
            const tbody = $('#tablePerangkat tbody');
            tbody.empty().append('<tr><td colspan="8" class="text-center">Terjadi kesalahan dalam memuat data</td></tr>');
        });
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
    </script>
@endsection