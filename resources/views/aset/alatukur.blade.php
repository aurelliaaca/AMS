@extends('layouts.sidebar')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Inter", sans-serif;
        }

        body {
            min-height: 100vh;
        }

        .container {
            box-shadow: 2px 2px 10px #9497f5;
            margin: 5px auto;
            padding: 20px;
            background-color: #fff;
            max-width: 100%;
            border-radius: 5px;
            transition: 0.3s linear all;
        }

        .container:hover {
            box-shadow: 4px 4px 20px #DADADA;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: #4f52ba;
            border-radius: 8px;
            padding: 8px 12px;
            width: 300px;
        }

        .search-bar input {
            border: none;
            background: none;
            outline: none;
            font-size: 14px;
            width: 100%;
            color: #fff;
        }

        .search-bar input::placeholder {
            color: #fff;
        }

        .search-bar svg {
            margin-right: 8px;
            color: #fff;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table thead th {
            background-color: #4f52ba;
            color: #fff;
            padding: 10px;
            text-align: left;
        }

        .table tbody tr {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .table tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        .table tbody td {
            padding: 10px;
            font-size: 14px;
            color: #333;
        }

        .edit-btn, .delete-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }

        .edit-btn {
            background-color: #4f52ba;
            color: white;
            margin-right: 5px;
        }

        .delete-btn {
            background-color: red;
            color: white;
        }
    </style>

    <div class="main">
        <div class="container">
            <div class="header">
                <h2><strong>Data Alat Ukur</strong></h2>
                <div class="search-bar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
                        <path fill="currentColor" d="M10 18a8 8 0 1 1 0-16a8 8 0 0 1 0 16Zm7.707-2.707a1 1 0 0 0 0 1.414l3 3a1 1 0 0 0 1.414-1.414l-3-3a1 1 0 0 0-1.414 0Z" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search" onkeyup="searchTable()" />
                </div>
            </div>
            <div class="table-responsive">
                <table class="table" id="alatUkurTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>RO</th>
                            <th>Kode</th>
                            <th>Nama Alat</th>
                            <th>Merk</th>
                            <th>Type</th>
                            <th>Serial Number</th>
                            <th>Tahun Perolehan</th>
                            <th>Kondisi Alat</th>
                            <th>Harga Pembelian</th>
                            <th>No Kontrak SPK</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alat_ukur as $alat)
                        <tr>
                            <td>{{ $alat->urutan}}</td>
                            <td>{{ $alat->RO }}</td>
                            <td>{{ $alat->kode }}</td>
                            <td>{{ $alat->nama_alat }}</td>
                            <td>{{ $alat->merk }}</td>
                            <td>{{ $alat->type }}</td>
                            <td>{{ $alat->serial_number }}</td>
                            <td>{{ $alat->tahun_perolehan }}</td>
                            <td>{{ $alat->kondisi_alat }}</td>
                            <td>{{ $alat->harga_pembelian }}</td>
                            <td>{{ $alat->no_kontrak_spk }}</td>
                            <td>
                                <button class="edit-btn">Edit</button>
                                <button class="delete-btn" onclick="deleteRow(this)">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function searchTable() {
            const input = document.getElementById('searchInput');
            if (!input) return;
            const filter = input.value.toLowerCase();
            const rows = document.querySelectorAll('.table tbody tr');

            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        }

        function deleteRow(btn) {
            const row = btn.closest('tr');
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                row.remove();
            }
        }
    </script>
@endsection
