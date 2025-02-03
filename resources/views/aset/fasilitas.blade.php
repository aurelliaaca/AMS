@extends('layouts.sidebar')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

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
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tr:hover {
            background-color: rgba(79, 82, 186, 0.2);
        }
        
        .select2-results__option--highlighted {
            background-color: #4f52ba !important;
            color: #fff;
        }
    </style>

    <div class="main">
        <div class="container">
            <div class="dropdown-container">
                <select id="ROFilter" class="filter-select">
                    <option value="">Pilih RO</option>
                    @foreach ($ro_list as $ro)
                        <option value="{{ $ro }}">{{ $ro }}</option>
                    @endforeach
                </select>

                <select id="UrutanFilter" class="filter-select">
                    <option value="">Pilih Nama POP</option>
                </select>

                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="Search" onkeyup="searchTable()" />
                </div>
            </div>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Urutan</th>
                            <th>RO</th>
                            <th>Nama POP</th>
                            <th>Perangkat</th>
                            <th>Merk</th>
                            <th>Tipe</th>
                            <th>Serial Number</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Update Site Visit</th>
                            <th>Tanggal Site Visit</th>
                            <th>Hostname</th>
                        </tr>
                    </thead>
                    <tbody id="dataRows">
                        @forelse ($fasilitas as $data)
                            <tr>
                                <td>{{ $data->urutan }}</td>
                                <td>{{ $data->RO }}</td>
                                <td>{{ $data->nama_POP }}</td>
                                <td>{{ $data->perangkat }}</td>
                                <td>{{ $data->merk }}</td>
                                <td>{{ $data->tipe }}</td>
                                <td>{{ $data->serial_Number }}</td>
                                <td>{{ $data->jumlah }}</td>
                                <td>{{ $data->satuan }}</td>
                                <td class="status {{ $data->status }}">{{ $data->status }}</td>
                                <td>{{ $data->keterangan }}</td>
                                <td>{{ $data->update_site_visit }}</td>
                                <td>{{ $data->tanggal_site_visit }}</td>
                                <td>{{ $data->hostname }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Sembunyikan semua baris saat halaman dimuat
            const rows = $('#dataRows tr');
            rows.hide();

            $('#ROFilter').select2({ placeholder: "Pilih RO", allowClear: true });
            $('#UrutanFilter').select2({ placeholder: "Pilih Nama POP", allowClear: true });

            $('#ROFilter').change(function() {
                const selectedRO = $(this).val();
                $('#UrutanFilter').val(null).trigger('change'); // Reset dropdown Nama POP
                rows.hide(); // Sembunyikan semua baris

                if (selectedRO) {
                    // Ambil nama POP yang sesuai dengan RO yang dipilih
                    $.get('/get-pop', { regions: selectedRO }, function(data) {
                        const popDropdown = $('#UrutanFilter');
                        popDropdown.empty().append('<option value="">Pilih Nama POP</option>'); // Reset dropdown

                        $.each(data, function(key, value) {
                            popDropdown.append(new Option(value, value)); // Tambahkan nama POP ke dropdown
                        });
                    });
                    
                    // Tampilkan semua data yang sesuai dengan RO yang dipilih
                    rows.each(function() {
                        const roCell = $(this).find('td').eq(1).text(); // Ambil nilai RO dari kolom pertama
                        if (roCell === selectedRO) {
                            $(this).show(); // Tampilkan baris yang sesuai
                        }
                    });
                }
            });

            $('#UrutanFilter').change(function() {
                const selectedRO = $('#ROFilter').val();
                const selectedPOP = $(this).val();
                rows.hide(); // Sembunyikan semua baris

                if (selectedRO && selectedPOP) {
                    rows.each(function() {
                        const roCell = $(this).find('td').eq(1).text(); 
                        const popCell = $(this).find('td').eq(2).text(); 
                        if (roCell === selectedRO && popCell === selectedPOP) {
                            $(this).show(); // Tampilkan baris yang sesuai
                        }
                    });
                }
            });
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