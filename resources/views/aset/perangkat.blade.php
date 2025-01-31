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
            border: 1px solid #ccc;
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
        
        h1 {
            text-align: center;
            margin-bottom: 5px;
            color: #2c3e50;
            font-size: 16px;
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
        }
        
        .select2-selection__placeholder {
            color: #777;
            font-size: 12px;
        }
        
        .select2-container--open .select2-selection {
            border-color: #007bff;
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
    </style>

    <div class="main">
        <div class="container">
            <div class="dropdown-container">
                <select id="region" name="region[]" multiple>
                    <option value="">Pilih Region</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                    @endforeach
                </select>

                <select id="site" name="site[]" multiple disabled>
                    <option value="">Pilih Site</option>
                </select>

                <div class="search-bar">
                    <input type="text" id="searchInput" class="custom-select" placeholder="Cari" onkeyup="searchTable()" />
                </div>
            </div>

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
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#region, #site').select2({ placeholder: "Pilih", allowClear: true });

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

                    loadPerangkatData(selectedRegions);
                } else {
                    loadPerangkatData(selectedRegions);
                }
            });

            $('#site').change(function() {
                const selectedRegions = $('#region').val();
                const selectedSites = $(this).val();
                loadPerangkatData(selectedRegions, selectedSites);
            });

            function loadPerangkatData(regions, sites = []) {
                if (!regions || regions.length === 0) return;

                $.get('/get-perangkat', { region: regions, site: sites }, function(response) {
                    const tbody = $('#tablePerangkat tbody');
                    tbody.empty();

                    if (response.perangkat.length === 0) {
                        tbody.append('<tr><td colspan="8" style="text-align: center;">Tidak ada data perangkat</td></tr>');
                        return;
                    }

                    $.each(response.perangkat, function(index, perangkat) {
                        tbody.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${perangkat.nama_region}</td>
                                <td>${perangkat.nama_site || '-'}</td>
                                <td>${perangkat.no_rack || '-'}</td>
                                <td>${perangkat.kode_pkt || '-'}</td>
                                <td>${perangkat.pkt_ke || '-'}</td>
                                <td>${perangkat.kode_brand || '-'}</td>
                                <td>${perangkat.type || '-'}</td>
                            </tr>
                        `);
                    });
                }).fail(function() {
                    const tbody = $('#tablePerangkat tbody');
                    tbody.empty().append('<tr><td colspan="8" style="text-align: center;">Terjadi kesalahan dalam memuat data</td></tr>');
                });
            }
        });

        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.querySelector('.table');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j]) {
                        if (cells[j].innerText.toLowerCase().includes(filter)) {
                            found = true;
                        }
                    }
                }

                rows[i].style.display = found ? '' : 'none';
            }
        }
    </script>
@endsection
